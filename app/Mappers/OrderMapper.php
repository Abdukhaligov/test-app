<?php

namespace App\Mappers;

use App\DTOs\CustomerDTO;
use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use App\Models\Order;

class OrderMapper
{
    public function fromModel(Order $model): OrderDTO
    {
        $customer = new CustomerDTO($model->customer->uuid, $model->customer->name, $model->customer->email);

        $items = $model->products->map(fn($p) => new OrderItemDTO(
            productId: $p->id,
            quantity: $p->pivot->quantity,
            unitPrice: $p->pivot->unit_price,
            productName: $p->name
        ))->toArray();

        return new OrderDTO ($customer, $items, $model->uuid);
    }
}