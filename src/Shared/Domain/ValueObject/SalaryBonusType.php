<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

enum SalaryBonusType: int
{
    case FIXED = 0;
    case PERCENTAGE = 1;

    public function getReadable(): string
    {
        return match($this) {
            self::FIXED => 'fixed amount',
            self::PERCENTAGE => 'percentage',
        };
    }
}