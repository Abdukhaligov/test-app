<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function findByIds(array $ids): Collection;
}