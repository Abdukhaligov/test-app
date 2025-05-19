<?php

namespace App\DTOs;

readonly class CustomerDTO
{
    public function __construct(public ?string $uuid, public ?string $name, public ?string $email)
    {
        if (!$this->uuid && (!$this->name || !$this->email)) {
            throw new \InvalidArgumentException(
                'Either uuid or name + email must be provided'
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }
    }
}