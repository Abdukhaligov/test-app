<?php

namespace App\DTOs;

use App\Models\Product;

readonly class ProductDTO
{
    public function __construct(
        public ?string $name = null,
        public ?float  $price = null,
        public ?int    $id = null,
    )
    {
        //
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price
        ];
    }

    public static function fromModel(Product $model): self
    {
        return new self(
            name: $model->name,
            price: $model->price,
            id: $model->id
        );
    }

    public static function hydrate(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            price: $data['price'] ?? null,
            id: $data['id'] ?? null
        );
    }
}