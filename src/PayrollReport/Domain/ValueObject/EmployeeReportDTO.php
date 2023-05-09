<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\ValueObject;
use App\Shared\Domain\ValueObject\SalaryBonusType;

class EmployeeReportDTO
{
    public function __construct(
        public readonly int $reportYear,
        public readonly int $reportMonth,
        public readonly SalaryBonusType $salaryBonusType,
        public readonly float $salaryBonusValue,
        public readonly float $salaryAmount,
        public readonly \DateTimeImmutable $employmentDate,
        public readonly string $employeeName,
        public readonly string $employeeSurname,
        public readonly string $departmentName,
    ) {
    }
}
