<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

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

        // 1. Generate cache keys for all requested IDs
        $cacheKeys = array_map(
            fn($id) => "product:{$id}",
            $processedIds
        );

        // 2. Attempt to retrieve all cached items at once
        $cachedProducts = Cache::many($cacheKeys);

        // 3. Separate found and missing items
        $found = [];
        $missingIds = [];

        foreach ($processedIds as $id) {
            $key = "product:{$id}";
            if (isset($cachedProducts[$key]) && $cachedProducts[$key] !== null) {
                $found[$id] = $cachedProducts[$key];
            } else {
                $missingIds[] = $id;
            }
        }

        // 4. Fetch missing items from DB
        $missingProducts = !empty($missingIds)
            ? $this->fetchAndCacheMissingProducts($missingIds)
            : [];

        // 5. Merge results while preserving order
        return new Collection(array_replace(array_flip($processedIds), $found, $missingProducts));
    }

    protected function fetchAndCacheMissingProducts(array $missingIds): array
    {
        $ttl = config('cache.ttl.product', 3600);

        $missingProducts = $this->model
            ->whereIn('id', $missingIds)
            ->get()
            ->keyBy('id');

        // Cache new items in bulk
        Cache::putMany($missingProducts->mapWithKeys(fn($product) => ["product:{$product->id}" => $product])->toArray(), $ttl);

        return $missingProducts->all();
    }

    // Simple cache invalidation for individual products
    public function forgetProductCache(int $productId): void
    {
        Cache::forget("product:{$productId}");
    }
}