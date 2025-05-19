<?php

namespace App\DTOs;

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
}