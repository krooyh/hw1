<?php

declare(strict_types=1);

namespace App\PayrollReport\App\Query;

use App\PayrollReport\Domain\Query\PayrollReportQueryParams;

class GetPayrollReport
{
    public function __construct(
        private readonly PayrollReportQueryParams $params,
    ) {
    }

    public function getParams(): PayrollReportQueryParams
    {
        return $this->params;
    }
}