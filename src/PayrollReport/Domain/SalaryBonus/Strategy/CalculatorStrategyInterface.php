<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\SalaryBonus\Strategy;

use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\Shared\Domain\ValueObject\SalaryBonusType;

interface CalculatorStrategyInterface
{
    public function supports(SalaryBonusType $salaryBonusType): bool;

    public function calculate(CalculationParamsDTO $dto): float;
}