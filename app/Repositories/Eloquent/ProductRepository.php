<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use App\DTOs\ProductDTO;

readonly class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model)
    {
        //   
    }

    public function findByIds(array $ids, bool $cached = true): Collection
    {
        if (!$cached) {
            return $this->model->query()->whereIn('id', $ids)->get();
        }

        $processedIds = array_unique($ids);
        if (empty($processedIds)) {
            return new Collection();
        }

        $cacheKeys = array_map(fn($id) => "product:{$id}", $processedIds);
        $cachedProducts = Cache::many($cacheKeys);

        // 3. Separate found and missing items
        $found = [];
        $missingIds = [];

        foreach ($processedIds as $id) {
            $key = "product:{$id}";
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

    protected function fetchAndCacheMissingProducts(array $missingIds): array
    {
        $ttl = config('cache.ttl.product', 3600);
        $products = $this->model
            ->whereIn('id', $missingIds)
            ->get()
            ->map(fn($model) => ProductDTO::fromModel($model));


        Cache::putMany(
            $products->mapWithKeys(
                fn(ProductDTO $dto) => ["product:{$dto->id}" => $dto->toArray()]
            )->toArray(),
            $ttl
        );

        return $products->keyBy('id')->all();
    }

    public function forgetProductCache(int $productId): void
    {
        Cache::forget("product:{$productId}");
    }
}