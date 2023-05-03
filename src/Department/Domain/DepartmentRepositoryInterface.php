<?php

declare(strict_types=1);

namespace App\Department\Domain;

use App\Department\Domain\Exception\DepartmentAlreadyExistsException;

interface DepartmentRepositoryInterface
{
    /**
     * @throws DepartmentAlreadyExistsException
     */
    public function persist(Department $department): void;
}