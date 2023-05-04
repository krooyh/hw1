<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\SalaryBonus\Strategy;

use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\Shared\App\Clock\ClockInterface;
use App\Shared\Domain\ValueObject\SalaryBonusType;

class FixedAmountCalculator implements CalculatorStrategyInterface
{
    private const YEAR_COUNT_STOP = 10;

    public function __construct(
        private readonly ClockInterface $clock
    ) {
    }
    public function supports(SalaryBonusType $salaryBonusType): bool
    {
        return SalaryBonusType::FIXED === $salaryBonusType;
    }

    public function calculate(CalculationParamsDTO $dto): float
    {
        $employmentDate = new \DateTimeImmutable();
        $employmentDate = $employmentDate->setDate(
            (int)$dto->employmentDate->format('Y'),
            (int)$dto->employmentDate->format('m'),
            1
        );

        $yearsWorked = $employmentDate->diff($this->clock->firstDayOfCurrentMonth())->y;

        return $dto->salaryBonusValue * min($yearsWorked, self::YEAR_COUNT_STOP);
    }
}