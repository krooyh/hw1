<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\ValueObject;

class PayrollReport
{
    /**
     * @param EmployeeReport[] $employeeReports
     */
    public function __construct(
        private readonly int $year,
        private readonly int $month,
        private readonly array $employeeReports,
    ) {
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getEmployeeReports(): array
    {
        return $this->employeeReports;
    }
}