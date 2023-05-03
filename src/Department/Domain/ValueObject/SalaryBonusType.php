<?php

declare(strict_types=1);

namespace App\Department\Domain\ValueObject;

enum SalaryBonusType: int
{
    case FIXED = 0;
    case PERCENTAGE = 1;

    public function getName(): string
    {
        return match($this) {
            self::FIXED => 'fixed amount',
            self::PERCENTAGE => 'percentage',
        };
    }
}