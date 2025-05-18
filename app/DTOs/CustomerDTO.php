<?php

namespace App\DTOs;

use App\Models\Customer;

class CustomerDTO
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

    public static function fromModel(Customer $model): self
    {
        return new self($model->uuid, $model->name, $model->email);
    }
}