<?php

namespace App\Services\Contracts;

use App\DTOs\OrderDTO;

interface OrderServiceInterface
{
    public function create(OrderDTO $orderDTO): OrderDTO;
}