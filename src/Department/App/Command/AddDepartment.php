<?php

declare(strict_types=1);

namespace App\Department\App\Command;

use App\Shared\Domain\ValueObject\SalaryBonusType;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints;

class AddDepartment
{
    public function __construct(
        private readonly Ulid $id,
        #[Constraints\Length(min: 1, max:10)]
        private readonly string $name,
        private readonly SalaryBonusType $salaryBonusType,
        #[Constraints\Regex(pattern: '/[0-9]{1,6}\.?[0-9]{1,2}/')]
        private readonly float $salaryBonusValue
    ) {
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSalaryBonusType(): SalaryBonusType
    {
        return $this->salaryBonusType;
    }

    public function getSalaryBonusValue(): float
    {
        return $this->salaryBonusValue;
    }

    #[Constraints\IsTrue]
    private function isCorrectBonusValue(): bool
    {
        if ($this->salaryBonusType ===SalaryBonusType::FIXED) {
            return true;
        }

        if ($this->salaryBonusType === SalaryBonusType::PERCENTAGE) {
            return $this->salaryBonusValue >= 0 && $this->salaryBonusValue <= 100;
        }

        return false;
    }
}