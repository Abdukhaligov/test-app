<?php

namespace App\Services;

use App\DTOs\OrderDTO;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\CustomerServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Database\DatabaseManager;

readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private DatabaseManager            $db,
        private CustomerServiceInterface   $customerService,
        private OrderRepositoryInterface   $orderRepository,
        private ProductRepositoryInterface $productRepository
    )
    {
        //
    }

    //TODO:: Consider returning a Data Transfer Object (DTO) instead of the raw Eloquent model 
    //       to improve separation of concerns and avoid exposing internal structure.
    public function create(OrderDTO $orderDTO): Order
    {
        return $this->db->transaction(function () use ($orderDTO) {
            // Customer UUID has already been validated in the request; redundant validation can be removed.
            $customerUuid = $orderDTO->customerUuid ?? $this->customerService->getCustomer($orderDTO->customer)->uuid;
            $productIds = array_map(fn($pd) => $pd->productId, $orderDTO->items);

            //TODO:: Cache product prices and implement an Event/Listener when the price changes.
            $products = $this->productRepository->findByIds($productIds, ['id', 'price']);

            $items = array_map(function ($i) use ($products) {
                $i->unitPrice = $products->find($i->productId)->price;

                return $i;
            }, $orderDTO->items);

            $items = array_map(function ($item) {
                return [
                    'product_id' => $item->productId,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unitPrice,
                ];
            }, $items);

            return $this->orderRepository->create($customerUuid, $items);
        });
    }
}