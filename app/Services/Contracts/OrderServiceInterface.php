<?php

namespace App\Services\Contracts;

use App\DTOs\OrderDTO;
use App\Models\Order;

interface OrderServiceInterface
{
    public function create(OrderDTO $orderDTO): Order;
}