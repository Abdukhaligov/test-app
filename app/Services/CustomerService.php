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
        return $this->customerRepository->findOrCreate($dto);
    }
}