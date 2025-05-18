<?php

namespace App\Services\Contracts;

use App\DTOs\CustomerDTO;

interface CustomerServiceInterface
{
    public function getCustomer(CustomerDTO $dto): CustomerDTO;
}