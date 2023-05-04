<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\Query;

enum FilterField: string
{
    case DEPARTMENT_NAME = 'department_name';
    case EMPLOYEE_NAME = 'employee_name';
    case EMPLOYEE_SURNAME = 'employee_surname';
}