<?php

namespace App\DTOs;

class OrderItemDTO
{
    public function __construct(
        public readonly int     $productId,
        public readonly int     $quantity,
        public ?float           $unitPrice = null,
        public readonly ?string $productName = null)
    {
        //
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice
        ];
    }
}