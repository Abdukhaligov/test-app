<?php

namespace App\Services;

use App\DTOs\CustomerDTO;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\Contracts\CustomerServiceInterface;

readonly class CustomerService implements CustomerServiceInterface
{
    public function __construct(private CustomerRepositoryInterface $customerRepository)
    {
        //
    }

    public function getCustomer(CustomerDTO $dto): CustomerDTO
    {
        $customer = $this->customerRepository->findOrCreate($dto->email, $dto->name);
        
        return new CustomerDTO($customer->uuid, $customer->name, $customer->email);
    }
}