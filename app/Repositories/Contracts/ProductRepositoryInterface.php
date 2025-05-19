<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function findByIds(array $ids): Collection;

    public function forgetProductCache(int $productId): void;
}