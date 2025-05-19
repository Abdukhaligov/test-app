<?php

namespace App\DTOs;

use App\Http\Requests\StoreOrderRequest;

readonly class OrderDTO
{
    public function __construct(
        public CustomerDTO $customer,
        /** @var OrderItemDTO[] */
        public array       $items,
        public ?string     $uuid = null,
        public ?float      $totalPrice = null
    )
    {
        //
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