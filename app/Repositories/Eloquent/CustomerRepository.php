<?php

namespace App\Repositories\Eloquent;

use App\DTOs\CustomerDTO;
use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\UniqueConstraintViolationException;

readonly class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(private Customer $model)
    {
        //
    }

    public function findOrCreate(CustomerDTO $dto): CustomerDTO
    {
        try {
            $customer = $this->model->firstOrCreate(
                ['email' => $dto->email],
                ['name' => $dto->name]
            );

            return CustomerDTO::fromModel($customer);
        } catch (UniqueConstraintViolationException $e) {
            return $this->findOrCreate($dto);
        }
    }
}