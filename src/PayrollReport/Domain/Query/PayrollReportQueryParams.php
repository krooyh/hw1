<?php

declare(strict_types=1);

namespace App\PayrollReport\Domain\Query;

use Symfony\Component\Validator\Constraints as Assert;

class PayrollReportQueryParams
{
    /**
     * @var Filter[]
     */
    private array $filters = [];

    public function __construct(
        #[Assert\Positive]
        #[Assert\Range(min: 1000, max: 3000)]
        private readonly int $year,
        #[Assert\Positive]
        #[Assert\Range(min: 1, max: 12)]
        private readonly int $month,
        private readonly ?OrderBy $orderBy = null,
    ) {
    }

    public function addFilter(Filter $filter): void
    {
        $this->filters[] = $filter;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getOrderBy(): ?OrderBy
    {
        return $this->orderBy;
    }
}
