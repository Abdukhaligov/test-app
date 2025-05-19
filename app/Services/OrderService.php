<?php

namespace App\Services;

use App\DTOs\CustomerDTO;
use App\DTOs\OrderDTO;
use App\DTOs\OrderItemDTO;
use App\Models\Order;
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
            $customerUuid = $orderDTO->customer->uuid ?? $this->customerService->getCustomer($orderDTO->customer)->uuid;

            $items = $this->prepareItems($orderDTO->items);
            $order = $this->orderRepository->create($customerUuid, array_map(fn(OrderItemDTO $i) => $i->toArray(), $items));

            return self::convertOrderToDTO($order);
        });
    }

    public function find(string $uuid): OrderDTO
    {
       return self::convertOrderToDTO($this->orderRepository->find($uuid));
    }

    /**
     * @param OrderItemDTO[] $items
     * @return OrderItemDTO[]
     */
    private function prepareItems(array $items): array
    {
        $productIds = array_map(fn($pd) => $pd->productId, $items);

        $products = $this->productRepository->findByIds($productIds)->pluck('price', 'id')->toArray();

        return collect($items)
            ->map(fn(OrderItemDTO $item) => new OrderItemDTO($item->productId, $item->quantity, $products[$item->productId]))
            ->all();
    }

    /**
     * @param Order $order
     * @return OrderDTO
     */
    private static function convertOrderToDTO(Order $order): OrderDTO
    {
        $items = $order->products->map(fn($p) => new OrderItemDTO(
            productId: $p->id,
            quantity: $p->pivot->quantity,
            unitPrice: $p->pivot->unit_price,
            productName: $p->name
        ));

        return new OrderDTO(
            new CustomerDTO($order->customer->uuid, $order->customer->name, $order->customer->email),
            $items->toArray(),
            $order->uuid,
            self::calculateTotalPrice(collect($items))
        );
    }

    /**
     * @param OrderItemDTO[]|Collection $items
     * @return float
     */
    private static function calculateTotalPrice(Collection $items): float
    {
        /** @var OrderItemDTO $item */
        return $items->reduce(fn($carry, OrderItemDTO $item) => $carry + ($item->quantity * $item->unitPrice), 0);
    }
}