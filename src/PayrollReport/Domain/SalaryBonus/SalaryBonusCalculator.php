<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\SalaryBonus;

use App\PayrollReport\Domain\Exception\SalaryBonusTypeNotSupported;
use App\PayrollReport\Domain\SalaryBonus\Strategy\CalculatorStrategyInterface;
use App\Shared\Domain\ValueObject\SalaryBonusType;

class SalaryBonusCalculator
{

    /**
     * @var CalculatorStrategyInterface[]
     */
    private array $calculators = [];

    public function setCalculator(CalculatorStrategyInterface $calculatorStrategy): void
    {
        $this->calculators[] = $calculatorStrategy;
    }

    /**
     * @throws SalaryBonusTypeNotSupported
     */
    public function calculate(SalaryBonusType $salaryBonusType, CalculationParamsDTO $dto): float {
        foreach ($this->calculators as $calculator) {
            if ($calculator->supports($salaryBonusType)) {
                return $calculator->calculate($dto);
            }
        }

        throw new SalaryBonusTypeNotSupported();
    }
}