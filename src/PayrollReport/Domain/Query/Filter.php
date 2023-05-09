<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\Query;

class Filter
{
    public function __construct(
        public readonly FilterField $field,
        public readonly mixed $value,
    ) {
    }
}