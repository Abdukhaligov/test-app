<?php

namespace App\Models;

use App\Events\ProductUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $name
 * @property mixed $price
 * @property mixed $id
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['name', 'price'];

    protected static function booted(): void
    {
        static::updated(function (Product $product) {
            $original = $product->getOriginal('price');
            $dirty = $product->getDirty();

            if (isset($dirty['price']) && $original != $dirty['price']) {
                ProductUpdated::dispatch($product->id);
            }
        });
    }
}
