<?php

declare(strict_types=1);

namespace TestsUnit\PayrollReport\Domain\SalaryBonus;

use App\PayrollReport\Domain\Exception\SalaryBonusTypeNotSupported;
use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\PayrollReport\Domain\SalaryBonus\SalaryBonusCalculator;
use App\PayrollReport\Domain\SalaryBonus\Strategy\FixedAmountCalculator;
use App\PayrollReport\Domain\SalaryBonus\Strategy\PercentageCalculator;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SalaryBonusCalculatorTest extends TestCase
{
    private SalaryBonusCalculator $calculator;

    public function setUp(): void
    {
        parent::setUp();

        $this->calculator = new SalaryBonusCalculator();
    }

    /**
     * @dataProvider salaryBonusTypeProvider
     */
    public function testItShouldThrowExceptionOnNotSupportedType(
        SalaryBonusType $type
    ): void {
        $this->expectException(SalaryBonusTypeNotSupported::class);

        $this->calculator->calculate(
            $type,
            new CalculationParamsDTO(
                0,
                0,
                new DateTimeImmutable(),
                new DateTimeImmutable(),
            )
        );
    }

    public function salaryBonusTypeProvider(): array
    {
        return [
            [SalaryBonusType::PERCENTAGE],
            [SalaryBonusType::FIXED],
        ];
    }

    public function testItShouldCalculateWithFixedAmountCalc(): void
    {
        $this->calculator->addCalculator(new FixedAmountCalculator());

        $calcParams = new CalculationParamsDTO(
            100.00,
            1000.00,
            (new DateTimeImmutable())->modify('-24 months'),
            new DateTimeImmutable(),
        );

        $result = $this->calculator->calculate(SalaryBonusType::FIXED, $calcParams);
        self::assertEquals(200.00, $result);
    }

    public function testItShouldCalculateWithPercentageCalc(): void
    {
        $this->calculator->addCalculator(new PercentageCalculator());

        $calcParams = new CalculationParamsDTO(
            50.00,
            1000.00,
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );

        $result = $this->calculator->calculate(SalaryBonusType::PERCENTAGE, $calcParams);
        self::assertEquals(500.00, $result);
    }
}