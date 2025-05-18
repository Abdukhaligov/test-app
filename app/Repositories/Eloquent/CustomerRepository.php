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
        $attempts = 0;

        do {
            try {
                return $this->model->firstOrCreate(
                    ['email' => $email],
                    ['name' => $name]);
            } catch (UniqueConstraintViolationException $e) {
                if (++$attempts > 3) throw $e;
            }
        } while (true);
    }
}