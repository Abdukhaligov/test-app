<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\UniqueConstraintViolationException;

readonly class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private Customer $model)
    {
        //
    }

    public function findOrCreate(string $email, string $name): Customer
    {
        try {
            return $this->model->firstOrCreate(
                ['email' => $email],
                ['name' => $name]
            );
        } catch (UniqueConstraintViolationException $e) {
            return $this->findOrCreate($email, $name);
        }
    }
}