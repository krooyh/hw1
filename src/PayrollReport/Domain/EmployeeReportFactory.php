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
use DateTimeImmutable;
use Exception;
use function sprintf;

class EmployeeReportFactory
{
    public function __construct(
        private readonly SalaryBonusCalculator $bonusCalculator
    ) {
    }

    /**
     * @throws SalaryBonusTypeNotSupported
     * @throws Exception
     */
    public function create(EmployeeReportDTO $dto): EmployeeReport
    {
        $bonusAmount = $this->bonusCalculator->calculate(
            $dto->salaryBonusType,
            new CalculationParamsDTO(
                $dto->salaryBonusValue,
                $dto->salaryAmount,
                $dto->employmentDate,
                $this->getCalculationDate($dto->reportYear, $dto->reportMonth),
            )
        );

        return new EmployeeReport(
            new Employee($dto->employeeName, $dto->employeeSurname),
            new Department($dto->departmentName),
            new MonthlySalary($dto->salaryAmount, $dto->salaryBonusType, $bonusAmount),
        );
    }

    /**
     * @throws Exception
     */
    private function getCalculationDate(int $reportYear, int $reportMonth): DateTimeImmutable
    {
        return new DateTimeImmutable(sprintf('%d-%d', $reportYear, $reportMonth));
    }
}