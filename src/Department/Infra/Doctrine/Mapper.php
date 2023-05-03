<?php

declare(strict_types=1);

namespace App\Department\Infra\Doctrine;

use App\Department\Domain\Department as DomainDepartment;
use App\Department\Domain\ValueObject\SalaryBonus;
use App\Department\Infra\Doctrine\Entity\Department;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use Symfony\Component\Uid\Ulid;

class Mapper
{
    public function fromDomain(DomainDepartment $domainDepartment): Department
    {
        return new Department(
            $domainDepartment->getId()->toBase32(),
            $domainDepartment->getName(),
            $domainDepartment->getSalaryBonus()->getType()->value,
            $domainDepartment->getSalaryBonus()->getValue()
        );
    }

    public function toDomain(Department $department): DomainDepartment
    {
        return new DomainDepartment(
            Ulid::fromBase32($department->id),
            $department->name,
            new SalaryBonus(
                SalaryBonusType::from($department->salaryBonusType),
                $department->salaryBonusValue,
            ),
        );
    }
}