<?php

declare(strict_types=1);

namespace App\Shared\App\Clock;

class Clock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    public function firstDayOfCurrentMonth(): \DateTimeImmutable
    {
        $now = $this->now();
        $now = $now->setDate(
            (int)$now->format('Y'),
            (int)$now->format('m'),
            1
        );
        return $now->setTime(0, 0);
    }
}