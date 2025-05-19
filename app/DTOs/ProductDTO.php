<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public ?string $name = null,
        public ?float  $price = null,
        public ?int    $id = null,
    )
    {
        //
    }
}