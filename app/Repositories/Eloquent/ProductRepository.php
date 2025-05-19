<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\DTOs\ProductDTO;

readonly class ProductRepository implements ProductRepositoryInterface
{
    public const CACHE_PREFIX = 'product:';

    public function __construct(protected Product $model)
    {
        //   
    }

    public function findByIds(array $ids, bool $cached = true): Collection
    {
        if (!$cached) {
            return $this->getByIdsQuery($ids);
        }

        $processedIds = array_unique($ids);
        if (empty($processedIds)) {
            return new Collection();
        }

        $cacheKeys = array_map(fn($id) => self::CACHE_PREFIX . $id, $processedIds);
        $cachedProducts = Cache::many($cacheKeys);

        // 3. Separate found and missing items
        $found = [];
        $missingIds = [];

        foreach ($processedIds as $id) {
            $key = self::CACHE_PREFIX . $id;
            if (isset($cachedProducts[$key])) {
                $found[$id] = ProductDTO::hydrate($cachedProducts[$key]);
            } else {
                $missingIds[] = $id;
            }
        }

        // 4. Fetch missing items from DB
        $missingProducts = !empty($missingIds)
            ? $this->fetchAndCacheMissingProducts($missingIds)
            : [];

        return new Collection(array_replace(
            array_flip($processedIds),
            $found,
            $missingProducts
        ));
    }
    
    private function getByIdsQuery($ids)
    {
        return $this->model
            ->whereIn('id', $ids)
            ->get()
            ->map(fn($model) => new ProductDTO( name: $model->name, price: $model->price, id: $model->id));
    } 

    protected function fetchAndCacheMissingProducts(array $missingIds): array
    {
        $ttl = config('cache.ttl.product', 3600);
        $products = $this->getByIdsQuery($missingIds);

        Cache::putMany(
            $products->mapWithKeys(
                fn(ProductDTO $dto) => [self::CACHE_PREFIX . $dto->id => $dto->toArray()]
            )->toArray(),
            $ttl
        );

        return $products->keyBy('id')->all();
    }

    public function forgetProductCache(int $productId): void
    {
        Cache::forget(self::CACHE_PREFIX . $productId);
    }
}