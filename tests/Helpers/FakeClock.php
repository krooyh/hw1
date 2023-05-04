<?php

declare(strict_types=1);

namespace TestsHelpers;

use App\Shared\App\Clock\ClockInterface;
use DateTimeImmutable;

class FakeClock implements ClockInterface
{
    protected static ?DateTimeImmutable $currentDateTime = null;

    public function now(): DateTimeImmutable
    {
        if (self::$currentDateTime instanceof DateTimeImmutable) {
            return clone self::$currentDateTime;
        }

        return new DateTimeImmutable();
    }

    public function firstDayOfCurrentMonth(): DateTimeImmutable
    {
        $now = $this->now();
        return $now->setDate(
            (int)$now->format('Y'),
            (int)$now->format('m'),
            1
        );
    }

    public static function clear(): void
    {
        self::$currentDateTime = null;
    }

    public static function setCurrent(DateTimeImmutable $dateTime): void
    {
        self::$currentDateTime = $dateTime;
    }
}