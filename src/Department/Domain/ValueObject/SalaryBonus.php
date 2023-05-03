<?php

declare(strict_types=1);

namespace App\Department\Domain\ValueObject;

use App\Shared\Domain\ValueObject\SalaryBonusType;

class SalaryBonus
{
    public function __construct(
        private readonly SalaryBonusType $salaryBonusType,
        private readonly float $salaryBonusValue,
    ) {
    }

    public function getType(): SalaryBonusType
    {
        return $this->salaryBonusType;
    }

    public function getValue(): float
    {
        return $this->salaryBonusValue;
    }
}