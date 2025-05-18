<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(protected Product $model)
    {
     //   
    }

    public function findByIds(array $ids, array $columns = ['*']): Collection
    {
        return $this->model->query()->whereIn('id', $ids)->select($columns)->get();
    }
}