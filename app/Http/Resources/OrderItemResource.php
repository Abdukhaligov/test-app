<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property mixed $pivot
 */
class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->id,
            'product_name' => $this->name,
            'quantity' => $this->pivot->quantity,
            'unit_price' => $this->pivot->unit_price,
        ];
    }
}
