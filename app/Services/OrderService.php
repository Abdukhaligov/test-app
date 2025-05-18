<?php

namespace App\Services;

use App\DTOs\OrderDTO;
use App\Mappers\OrderItemMapper;
use App\Mappers\OrderMapper;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\CustomerServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Database\DatabaseManager;

readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private DatabaseManager            $db,
        private OrderMapper                $orderMapper,
        private CustomerServiceInterface   $customerService,
        private OrderRepositoryInterface   $orderRepository,
        private ProductRepositoryInterface $productRepository
    )
    {
        //
    }

    /**
     * @throws \Throwable
     */
    public function create(OrderDTO $orderDTO): OrderDTO
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

            $items = array_map(fn($i) => OrderItemMapper::toDBFormat($i), $items);
            
            return $this->orderMapper->fromModel($this->orderRepository->create($customerUuid, $items));
        });
    }
    
    public function find(string $uuid): OrderDTO
    {
        $order = $this->orderRepository->find($uuid);

        return $this->orderMapper->fromModel($order);
    }
}