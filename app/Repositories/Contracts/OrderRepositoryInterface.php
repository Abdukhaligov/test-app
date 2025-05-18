<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * @param string $customerUuid
     * @param array $items
     * @return Order
     */
    public function create(string $customerUuid, array $items): Order;
    public function find(string $uuid): ?Order;
}