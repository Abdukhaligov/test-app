<?php

namespace App\Mappers;

use App\DTOs\OrderItemDTO;

class OrderItemMapper
{
    public static function toDBFormat(OrderItemDTO $item): array
    {
        return [
            'product_id' => $item->productId,
            'quantity' => $item->quantity,
            'unit_price' => $item->unitPrice
        ];
    }
}