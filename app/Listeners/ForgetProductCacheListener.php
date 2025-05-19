<?php

namespace App\Listeners;

use App\Services\Contracts\ProductServiceInterface;

readonly class ForgetProductCacheListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private ProductServiceInterface $productService)
    {
        //
    }


    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $this->productService->forgetProductCache($event->productId);
    }
}
