<?php

declare(strict_types=1);

namespace TestsUnit\PayrollReport\Domain\SalaryBonus;

use App\PayrollReport\Domain\Exception\SalaryBonusTypeNotSupported;
use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\PayrollReport\Domain\SalaryBonus\SalaryBonusCalculator;
use App\Shared\Domain\ValueObject\SalaryBonusType;
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
                new \DateTimeImmutable()
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

    public function testItShouldCalculateWithCorrectStrategy(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}