<?php

namespace App\Services;

use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\CustomerServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;

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

    /**
     * @throws \Throwable
     */
    public function create(OrderDTO $orderDTO): OrderDTO
    {
        return $this->db->transaction(function () use ($orderDTO) {
            // Customer UUID has already been validated in the request; redundant validation can be removed.
            $customerUuid = $orderDTO->customerUuid ?? $this->customerService->getCustomer($orderDTO->customer)->uuid;

            $items = $this->prepareItems($orderDTO->items);
            
            $dto = OrderDTO::fromModel($this->orderRepository->create($customerUuid, $items));
            $dto->totalPrice = self::calculateTotalPrice(collect($dto->items));

            return $dto;
        });
    }

    public function find(string $uuid): OrderDTO
    {
        $order = $this->orderRepository->find($uuid);
        $dto = OrderDTO::fromModel($order);

        $dto->totalPrice = self::calculateTotalPrice(collect($dto->items));

        return $dto;
    }

    /**
     * @param OrderItemDTO[] $items
     * @return array
     */
    private function prepareItems(array $items): array
    {
        $productIds = array_map(fn($pd) => $pd->productId, $items);
        
        $products = $this->productRepository->findByIds($productIds)->pluck('price', 'id')->toArray();
        
        return collect($items)->map(function ($item) use ($products) {
            $item->unitPrice = $products[$item->productId];

            return $item->toArray();
        })->all();
    }

    /**
     * @param OrderItemDTO[]|Collection $items
     * @return float
     */
    private static function calculateTotalPrice(Collection $items): float
    {
        /** @var OrderItemDTO $item */
        return $items->reduce(fn($carry, $item) => $carry + ($item->quantity * $item->unitPrice), 0);
    }
}