<?php

namespace App\DTOs;

readonly class CustomerDTO
{
    public function __construct(public ?string $uuid, public ?string $name, public ?string $email)
    {
        //
    }
}