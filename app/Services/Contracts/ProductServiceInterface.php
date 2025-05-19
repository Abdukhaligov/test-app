<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    public function findByIds(array $productIds): Collection;
    public function forgetProductCache(int $productId): void;
}