<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\ValueObject;

use App\Shared\Domain\ValueObject\SalaryBonusType;

class MonthlySalary
{
    public function __construct(
        private readonly float $baseAmount,
        private readonly SalaryBonusType $salaryBonusType,
        private readonly float $bonusAmount,
    ) {
    }

    public function getBaseAmount(): float
    {
        return $this->baseAmount;
    }

    public function getSalaryBonusType(): SalaryBonusType
    {
        return $this->salaryBonusType;
    }

    public function getBonusAmount(): float
    {
        return $this->bonusAmount;
    }

    public function getTotal(): float
    {
        return $this->baseAmount + $this->bonusAmount;
    }
}