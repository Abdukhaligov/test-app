<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property string $uuid
 * @property Customer $customer
 * @property Collection $products
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'customer_name' => $this->customer->name,
            'customer_email' => $this->customer->email,
            'total_price' => $this->products->sum('price'),
            'items' => OrderItemResource::collection($this->products)
        ];
    }
}
