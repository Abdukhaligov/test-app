<?php

namespace App\Mappers;

use App\DTOs\CustomerDTO;
use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use App\Http\Requests\StoreOrderRequest;

class OrderRequestMapper
{
    public static function toDTO(StoreOrderRequest $request): OrderDTO
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