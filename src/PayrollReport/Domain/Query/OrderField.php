<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\Query;

enum OrderField: string
{
    case EMPLOYEE_NAME = 'employee_name';
    case EMPLOYEE_SURNAME = 'employee_surname';
    case DEPARTMENT_NAME = 'department_name';
    case MONTHLY_SALARY = 'monthly_salary';
    case BONUS_AMOUNT = 'bonuses_amount';
    case BONUS_TYPE = 'bonuses_type';
    case SALARY_TOTAL = 'salary_total';
}