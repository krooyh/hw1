<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\ValueObject;

class Department
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}