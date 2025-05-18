<?php

namespace App\Repositories\Contracts;

use App\DTOs\CustomerDTO;

interface CustomerRepositoryInterface
{
    public function findOrCreate(CustomerDTO $dto): CustomerDTO;
}