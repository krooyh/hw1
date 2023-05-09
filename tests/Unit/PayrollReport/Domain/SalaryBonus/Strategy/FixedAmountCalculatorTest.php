<?php

declare(strict_types=1);

namespace TestsUnit\PayrollReport\Domain\SalaryBonus\Strategy;

use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\PayrollReport\Domain\SalaryBonus\Strategy\FixedAmountCalculator;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FixedAmountCalculatorTest extends TestCase
{
    private FixedAmountCalculator $calculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->calculator = new FixedAmountCalculator();
    }

    public function testItShouldSupportFixed(): void
    {
        self::assertTrue($this->calculator->supports(SalaryBonusType::FIXED));
        self::assertFalse($this->calculator->supports(SalaryBonusType::PERCENTAGE));
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
            '3 years worked' => [
                new CalculationParamsDTO(
                    100.00,
                    0,
                    new DateTimeImmutable('2020-01-01'),
                    new DateTimeImmutable('2023-05-04'),
                ),
                300.00
            ],
            '3 years worked #2' => [
                new CalculationParamsDTO(
                    100.00,
                    0,
                    new DateTimeImmutable('2015-01-01'),
                    new DateTimeImmutable('2018-05-04'),
                ),
                300.00
            ],
            '5 years worked' => [
                new CalculationParamsDTO(
                    200.00,
                    100,
                    new DateTimeImmutable('2018-05-09'), //same month, day is omitted
                    new DateTimeImmutable('2023-05-04'),
                ),
                1000.00
            ],
            '0 years worked' => [
                new CalculationParamsDTO(
                    200.00,
                    100,
                    new DateTimeImmutable('2023-01-01'),
                    new DateTimeImmutable('2023-05-04'),
                ),
                0.00
            ],
            '20 years worked' => [
                new CalculationParamsDTO(
                    100.00,
                    0,
                    new DateTimeImmutable('2003-01-01'),
                    new DateTimeImmutable('2023-05-04')
                ),
                1000.00 //calculation capped at 10 years
            ],
            '15 years worked' => [
                new CalculationParamsDTO(
                    100.00,
                    0,
                    new DateTimeImmutable('2003-01-01'),
                    new DateTimeImmutable('2018-05-04')
                ),
                1000.00 //calculation capped at 10 years
            ],
        ];
    }
}