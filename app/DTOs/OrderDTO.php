<?php

namespace App\DTOs;

class OrderDTO
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
}