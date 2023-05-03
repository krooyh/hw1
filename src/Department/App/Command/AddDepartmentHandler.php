<?php

declare(strict_types=1);

namespace App\Department\App\Command;

use App\Department\Domain\Department;
use App\Department\Domain\DepartmentRepositoryInterface;
use App\Department\Domain\ValueObject\SalaryBonus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class AddDepartmentHandler
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $departmentRepository
    ) {
    }
    public function __invoke(AddDepartment $command): void
    {
        $department = new Department(
            $command->getId(),
            $command->getName(),
            new SalaryBonus(
                $command->getSalaryBonusType(),
                $command->getSalaryBonusValue(),
            )
        );

        $this->departmentRepository->persist($department);
    }
}