<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
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
    public function index(string $uuid): \Illuminate\Http\JsonResponse
    {
        return response()->json(OrderResource::make($this->orderService->find($uuid)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(OrderResource::make($this->orderService->create($request->toDTO())));
    }
}
