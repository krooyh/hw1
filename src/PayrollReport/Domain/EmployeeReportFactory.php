<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain;

use App\PayrollReport\Domain\Exception\SalaryBonusTypeNotSupported;
use App\PayrollReport\Domain\SalaryBonus\CalculationParamsDTO;
use App\PayrollReport\Domain\SalaryBonus\SalaryBonusCalculator;
use App\PayrollReport\Domain\ValueObject\Department;
use App\PayrollReport\Domain\ValueObject\Employee;
use App\PayrollReport\Domain\ValueObject\EmployeeReport;
use App\PayrollReport\Domain\ValueObject\EmployeeReportDTO;
use App\PayrollReport\Domain\ValueObject\MonthlySalary;

class EmployeeReportFactory
{
    public function __construct(
        private readonly SalaryBonusCalculator $bonusCalculator
    ) {
    }

    /**
     * @throws SalaryBonusTypeNotSupported
     */
    public function create(EmployeeReportDTO $dto): EmployeeReport
    {
        $bonusAmount = $this->bonusCalculator->calculate(
            new CalculationParamsDTO(
                $dto->salaryBonusType,
                $dto->salaryBonusValue,
                $dto->salaryAmount,
                $dto->employmentDate
            )
        );

        return new EmployeeReport(
            new Employee($dto->employeeName, $dto->employeeSurname),
            new Department($dto->departmentName),
            new MonthlySalary($dto->salaryAmount, $dto->salaryBonusType, $bonusAmount),
        );
    }
}