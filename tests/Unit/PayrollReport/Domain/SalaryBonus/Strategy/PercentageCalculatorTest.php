<?php

declare(strict_types=1);

namespace TestsUnit\PayrollReport\Domain\SalaryBonus\Strategy;

use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\PayrollReport\Domain\SalaryBonus\Strategy\PercentageCalculator;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PercentageCalculatorTest extends TestCase
{
    private PercentageCalculator $calculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->calculator = new PercentageCalculator();
    }

    public function testItShouldSupportFixed(): void
    {
        self::assertTrue($this->calculator->supports(SalaryBonusType::PERCENTAGE));
        self::assertFalse($this->calculator->supports(SalaryBonusType::FIXED));
    }

    /**
     * @dataProvider calculationParamsProvider
     */
    public function testItShouldCalculate(CalculationParamsDTO $calculationParamsDTO, float $expected): void
    {
        $result = $this->calculator->calculate($calculationParamsDTO);

        self::assertEquals($expected, $result);
    }

    public function calculationParamsProvider(): array
    {
        return [
            '100% of salary' => [
                new CalculationParamsDTO(
                    100.00,
                    1000,
                    new DateTimeImmutable('2020-01-01'),
                ),
                1000.00
            ],
            '20% of salary' => [
                new CalculationParamsDTO(
                    20.00,
                    1000,
                    new DateTimeImmutable('2020-01-01'),
                ),
                200.00
            ],
        ];
    }
}