<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\Query;

class OrderBy
{
    public function __construct(
        public readonly OrderField $orderField,
        public readonly OrderDirection $orderDirection,
    ) {
    }
}