<?php

declare(strict_types=1);

namespace App\Department\Domain;

use App\Department\Domain\ValueObject\SalaryBonus;
use Symfony\Component\Uid\Ulid;

class Department
{
    public function __construct(
        private readonly Ulid $id,
        private string $name,
        private SalaryBonus $salaryBonus,
    ) {
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSalaryBonus(): SalaryBonus
    {
        return $this->salaryBonus;
    }
}