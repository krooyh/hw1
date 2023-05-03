<?php

declare(strict_types=1);

namespace App\Department\Infra\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('departments')]
class Department
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'string', length: 26, unique: true)]
        public string $id,
        #[ORM\Column(type: 'string', unique: true)]
        public string $name,
        #[ORM\Column(type: 'smallint', options: ["unsigned" => true])]
        public int $salaryBonusType,
        #[ORM\Column(type: 'decimal', precision: 8, scale: 2 )]
        public float $salaryBonusValue
    ) {
    }
}
