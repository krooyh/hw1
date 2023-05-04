<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\ValueObject;

class EmployeeReport
{
    public function __construct(
        private readonly Employee $employee,
        private readonly Department $department,
        private readonly MonthlySalary $salary,
    ) {
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function getSalary(): MonthlySalary
    {
        return $this->salary;
    }
}