<?php

declare(strict_types=1);

namespace TestsUnit\Department\App\Command;

use App\Department\App\Command\AddDepartment;
use App\Department\App\Command\AddDepartmentHandler;
use App\Department\Domain\Department;
use App\Department\Domain\DepartmentRepositoryInterface;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

class AddDepartmentHandlerTest extends TestCase
{
    private const NAME = 'HR';
    private const SALARY_BONUS_VALUE = 10.5;

    private DepartmentRepositoryInterface|MockObject $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(DepartmentRepositoryInterface::class);
    }

    public function testHandle(): void
    {
        $handler = new AddDepartmentHandler($this->repository);

        $id = new Ulid();
        $command = new AddDepartment(
            $id,
            self::NAME,
            SalaryBonusType::FIXED,
            self::SALARY_BONUS_VALUE
        );

        $this->repository
            ->expects($this->once())
            ->method('persist')
            ->with(
                $this->callback(
                    function (Department $department) use ($id): bool {
                        $this->assertEquals($id, $department->getId());
                        $this->assertEquals(self::NAME, $department->getName());
                        $this->assertEquals(SalaryBonusType::FIXED, $department->getSalaryBonus()->getType());
                        $this->assertEquals(self::SALARY_BONUS_VALUE, $department->getSalaryBonus()->getValue());

                        return true;
                    }
                )
            );

        $handler($command);
    }

    public function testValidationFailed(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
