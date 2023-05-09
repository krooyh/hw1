<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\SalaryBonus;

class CalculationParamsDTO
{
    public function __construct(
        public readonly float $salaryBonusValue,
        public readonly float $salaryAmount,
        public readonly \DateTimeImmutable $employmentDate,
        public readonly \DateTimeImmutable $calculationDate,
    ) {
    }
}