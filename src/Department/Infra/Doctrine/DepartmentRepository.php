<?php

declare(strict_types=1);

namespace App\Department\Infra\Doctrine;

use App\Department\Domain\Department;
use App\Department\Domain\DepartmentRepositoryInterface;
use App\Department\Domain\Exception\DepartmentAlreadyExistsException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Mapper $mapper
    ) {
    }

    /**
     * @throws DepartmentAlreadyExistsException
     */
    public function persist(Department $department): void
    {
        $item = $this->mapper->fromDomain($department);
        try {
            $this->entityManager->persist($item);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new DepartmentAlreadyExistsException();
        }
    }
}