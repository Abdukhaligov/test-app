<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

readonly class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(private Order $model)
    {
        //
    }

    /**
     * @param string $customerUuid
     * @param array $items
     * @return Order
     */
    public function create(string $customerUuid, array $items): Order
    {
        /** @var Order $order */
        $order = $this->model->create(['customer_uuid' => $customerUuid]);

        $order->products()->attach($items);

        return $order;
    }

    public function find(string $uuid): ?Order
    {
        return $this->model->with('products')->find($uuid);
    }
}