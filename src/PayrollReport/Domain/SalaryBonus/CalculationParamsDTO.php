<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\SalaryBonus;

use App\Shared\Domain\ValueObject\SalaryBonusType;

class CalculationParamsDTO
{
    public function __construct(
        public readonly SalaryBonusType $salaryBonusType,
        public readonly float $salaryBonusValue,
        public readonly float $salaryAmount,
        public readonly \DateTimeImmutable $employmentDate,
    ) {
    }
}