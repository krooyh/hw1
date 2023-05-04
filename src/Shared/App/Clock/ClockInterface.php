<?php

declare(strict_types=1);

namespace App\Shared\App\Clock;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
    public function firstDayOfCurrentMonth(): \DateTimeImmutable;
}