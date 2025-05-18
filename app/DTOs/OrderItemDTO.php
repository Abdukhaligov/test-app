<?php

namespace App\DTOs;

class OrderItemDTO
{
    public function __construct(
        public int     $productId,
        public int     $quantity,
        public ?float  $unitPrice = null,
        public ?string $productName = null)
    {
        //
    }
}