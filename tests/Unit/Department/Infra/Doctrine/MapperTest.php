<?php

declare(strict_types=1);

namespace TestsUnit\Department\Infra\Doctrine;

use App\Department\Domain\Department as DomainDepartment;
use App\Department\Domain\ValueObject\SalaryBonus;
use App\Department\Infra\Doctrine\Entity\Department;
use App\Department\Infra\Doctrine\Mapper;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

class MapperTest extends TestCase
{

    private const NAME = 'HR';
    private const SALARY_BONUS_TYPE = 0;
    private const SALARY_BONUS_VALUE = 150;

    private Mapper $mapper;

    public function setUp(): void
    {
        parent::setUp();

        $this->mapper = new Mapper();
    }

    public function testToDomain(): void
    {
        $id = new Ulid();
        $department = new Department(
            $id->toBase32(),
            self::NAME,
            self::SALARY_BONUS_TYPE,
            self::SALARY_BONUS_VALUE,
        );

        $domainEntity = $this->mapper->toDomain($department);

        self::assertEquals($id, $domainEntity->getId());
        self::assertSame(self::NAME, $domainEntity->getName());
        self::assertSame(self::SALARY_BONUS_TYPE, $domainEntity->getSalaryBonus()->getType()->value);
        self::assertEquals(self::SALARY_BONUS_VALUE, $domainEntity->getSalaryBonus()->getValue());
    }

    public function testFromDomain(): void
    {
        $id = new Ulid();
        $department = new DomainDepartment(
            $id,
            self::NAME,
            new SalaryBonus(
                SalaryBonusType::from(self::SALARY_BONUS_TYPE),
                self::SALARY_BONUS_VALUE,
            )
        );

        $doctrineEntity = $this->mapper->fromDomain($department);

        self::assertSame($id->toBase32(), $doctrineEntity->id);
        self::assertSame(self::NAME, $doctrineEntity->name);
        self::assertSame(self::SALARY_BONUS_TYPE, $doctrineEntity->salaryBonusType);
        self::assertEquals(self::SALARY_BONUS_VALUE, $doctrineEntity->salaryBonusValue);
    }
}
