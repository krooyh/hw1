<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\SalaryBonus\Strategy;

use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\Shared\Domain\ValueObject\SalaryBonusType;

class PercentageCalculator implements CalculatorStrategyInterface
{
    public function supports(SalaryBonusType $salaryBonusType): bool
    {
        return SalaryBonusType::PERCENTAGE === $salaryBonusType;
    }

    public function calculate(CalculationParamsDTO $dto): float
    {
        return $dto->salaryAmount * ($dto->salaryBonusValue / 100);
    }
}