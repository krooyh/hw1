<?php

declare(strict_types=1);

namespace App\PayrollReport\App\Formatter;

use App\PayrollReport\Domain\ValueObject\EmployeeReport;
use App\PayrollReport\Domain\ValueObject\PayrollReport;

class SymfonyCommandTableFormatter
{
    public function getRows(PayrollReport $payrollReport): array
    {
        return array_map(
            static function (EmployeeReport $employeeReport): array {
                return [
                    $employeeReport->getEmployee()->getName(),
                    $employeeReport->getEmployee()->getSurname(),
                    $employeeReport->getDepartment()->getName(),
                    $employeeReport->getSalary()->getBaseAmount(),
                    $employeeReport->getSalary()->getBonusAmount(),
                    $employeeReport->getSalary()->getSalaryBonusType()->getReadable(),
                    $employeeReport->getSalary()->getTotal(),
                ];
            },
            $payrollReport->getEmployeeReports()
        );
    }

    public function getHeaders(): array
    {
        return [
            'Name',
            'Surname',
            'Department',
            'Remuneration base',
            'Addition to base',
            'Bonus type',
            'Salary with bonus',
        ];
    }
}