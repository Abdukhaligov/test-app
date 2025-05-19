<?php

namespace App\Http\Controllers;

use App\DTOs\OrderDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\Contracts\CustomerServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(
        protected readonly CustomerServiceInterface $customerService,
        protected readonly OrderServiceInterface    $orderService,
        protected readonly ProductServiceInterface  $productService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $uuid): \Illuminate\Http\JsonResponse
    {
        return response()->json(OrderResource::make($this->orderService->find($uuid)));
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(StoreOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        //Note: Laravel's "exists" rule is not optimized - used manual check to avoid N+1 queries.
        $productIds = collect($request->get('products'))->pluck('product_id');
        $existingIds = $this->productService->findByIds($productIds->toArray())->map(fn($pd) => $pd->id);
        
        $invalidIds = $productIds->diff($existingIds);
        
        if ($invalidIds->isNotEmpty()) {
            $messages = [];
            foreach ($invalidIds as $key => $invalidId) {
                $messages['products.' . $key . '.product_id'] = 'Invalid product id: ' . $invalidId;
            }

            throw \Illuminate\Validation\ValidationException::withMessages($messages);
        }

        return response()->json(
            OrderResource::make($this->orderService->create(OrderDTO::fromRequest($request)))
        );
    }
}
