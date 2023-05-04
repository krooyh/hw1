<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\Query;

enum OrderDirection: string
{
    case ASC = 'ASC';
    case DESC = 'DESC';
}