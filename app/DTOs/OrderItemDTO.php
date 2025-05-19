<?php

namespace App\DTOs;

readonly class OrderItemDTO
{
    public function __construct(
        public int     $productId,
        public int     $quantity,
        public ?float  $unitPrice = null,
        public ?string $productName = null)
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