<?php

namespace App\Http\Resources;

use App\DTOs\OrderDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var OrderDTO $this */
        return [
            'id' => $this->uuid,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'total_price' => array_sum(array_column($this->items, 'unitPrice')),
            'items' => OrderItemResource::collection($this->items)
        ];
    }
}
