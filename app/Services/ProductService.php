<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Support\Collection;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
        //
    }

    public function findByIds(array $productIds): Collection
    {
        return $this->productRepository->findByIds($productIds)
            ->map(fn(ProductDTO $product) => new ProductDTO(price: $product->price, id: $product->id));
    }

    public function forgetProductCache(int $productId): void
    {
        $this->productRepository->forgetProductCache($productId);
    }
}