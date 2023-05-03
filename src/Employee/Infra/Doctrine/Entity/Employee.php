<?php

declare(strict_types=1);

namespace App\Employee\Infra\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('employees')]
class Employee
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'string', length: 26, unique: true)]
        public string $id,
        #[ORM\Column(type: 'string', length: 20)]
        public string $name,
        #[ORM\Column(type: 'string', length: 30)]
        public string $surname,
        #[ORM\Column(type: 'date_immutable')]
        public DateTimeImmutable $employmentDate,
        #[ORM\Column(type: 'date_immutable', nullable: true)]
        public ?DateTimeImmutable $terminationDate,
        #[ORM\Column(type: 'string', length: 26)]
        public string $departmentId,
        #[ORM\Column(type:'decimal', precision: 8, scale: 2)]
        public float $monthlySalary,
    ) {
    }
}