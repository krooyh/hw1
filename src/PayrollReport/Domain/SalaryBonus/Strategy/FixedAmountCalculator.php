<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\SalaryBonus\Strategy;

use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\Shared\Domain\ValueObject\SalaryBonusType;

class FixedAmountCalculator implements CalculatorStrategyInterface
{
    private const YEAR_COUNT_STOP = 10;

    public function supports(SalaryBonusType $salaryBonusType): bool
    {
        return SalaryBonusType::FIXED === $salaryBonusType;
    }

    public function calculate(CalculationParamsDTO $dto): float
    {
        $employmentDate = $this->getFirstDayOfMonthDate($dto->employmentDate);
        $calculationDate = $this->getFirstDayOfMonthDate($dto->calculationDate);

        $yearsWorked = $employmentDate->diff($calculationDate)->y;

        return $dto->salaryBonusValue * min($yearsWorked, self::YEAR_COUNT_STOP);
    }

    private function getFirstDayOfMonthDate(\DateTimeImmutable $date): \DateTimeImmutable
    {
        $newDate = new \DateTimeImmutable();
        $newDate = $newDate->setDate(
            (int)$date->format('Y'),
            (int)$date->format('m'),
            1,
        );

        return $newDate->setTime(0, 0);
    }
}