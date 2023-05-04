<?php

declare(strict_types=1);

namespace TestsUnit\PayrollReport\Domain\SalaryBonus\Strategy;

use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\PayrollReport\Domain\SalaryBonus\Strategy\FixedAmountCalculator;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use DateTimeImmutable;
use TestsHelpers\FakeClock;
use PHPUnit\Framework\TestCase;

class FixedAmountCalculatorTest extends TestCase
{
    private FakeClock $clock;
    private FixedAmountCalculator $calculator;

    public function setUp(): void
    {
        parent::setUp();
        $this->clock = new FakeClock();
        FakeClock::clear();
        $this->calculator = new FixedAmountCalculator($this->clock);
    }

    public function testItShouldSupportFixed(): void
    {
        self::assertTrue($this->calculator->supports(SalaryBonusType::FIXED));
        self::assertFalse($this->calculator->supports(SalaryBonusType::PERCENTAGE));
    }

    /**
     * @dataProvider calculationParamsProvider
     */
    public function testItShouldCalculate(DateTimeImmutable $now, CalculationParamsDTO $calculationParamsDTO, float $expected): void
    {
        FakeClock::setCurrent($now);
        $result = $this->calculator->calculate($calculationParamsDTO);

        self::assertEquals($expected, $result);
    }

    public function calculationParamsProvider(): array
    {
        return [
            '3 years worked' => [
                new DateTimeImmutable('2023-05-04'),
                new CalculationParamsDTO(
                    100.00,
                    0,
                    new DateTimeImmutable('2020-01-01'),
                ),
                300.00
            ],
            '5 years worked' => [
                new DateTimeImmutable('2023-05-04'),
                new CalculationParamsDTO(
                    200.00,
                    100,
                    new DateTimeImmutable('2018-05-09'), //same month, day is omitted
                ),
                1000.00
            ],
            '0 days worked' => [
                new DateTimeImmutable('2023-05-04'),
                new CalculationParamsDTO(
                    200.00,
                    100,
                    new DateTimeImmutable('2023-01-01'), //same month, day is omitted
                ),
                0.00
            ],

        ];
    }
}