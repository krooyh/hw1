<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\ValueObject;

class Employee
{
    public function __construct(
        private readonly string $name,
        private readonly string $surname,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}