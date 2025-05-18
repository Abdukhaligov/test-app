<?php

namespace App\Providers;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Services\Contracts\CustomerServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\CustomerService;
use App\Services\OrderService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(
            CustomerRepositoryInterface::class,
            CustomerRepository::class
        );
        
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );
        
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        
        //Services
        $this->app->bind(
            CustomerServiceInterface::class,
            CustomerService::class
        );
        
        $this->app->bind(
            OrderServiceInterface::class,
            OrderService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
