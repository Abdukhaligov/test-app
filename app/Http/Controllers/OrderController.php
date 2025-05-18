<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Contracts\CustomerServiceInterface;
use App\Services\Contracts\OrderServiceInterface;

class OrderController extends Controller
{
    public function __construct(
        protected readonly CustomerServiceInterface $customerService,
        protected readonly OrderServiceInterface    $orderService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $orderDTO = $request->toDTO();
        $order = $this->orderService->create($orderDTO);

        return response()->json(OrderResource::make($order));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
