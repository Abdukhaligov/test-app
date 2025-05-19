<?php

namespace App\DTOs;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;

class OrderDTO
{
    public function __construct(
        public readonly CustomerDTO $customer,
        /** @var OrderItemDTO[] */
        public readonly array       $items,
        public readonly ?string     $uuid = null,
        public ?float               $totalPrice = null
    )
    {
        //
    }

    public static function fromModel(Order $model): self
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

    public static function fromRequest(StoreOrderRequest $request): self
    {
        $customerDTO = new CustomerDTO($request->customer_uuid, $request->customer_name, $request->customer_email);

        $productsDTO = array_map(
            fn(array $item) => new OrderItemDTO(
                productId: $item['product_id'],
                quantity: $item['quantity'],
            ),
            $request->products
        );

        return new OrderDTO($customerDTO, $productsDTO);
    }
}