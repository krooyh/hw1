<?php

declare(strict_types=1);

namespace TestsUnit\PayrollReport\Infra\Doctrine;

use App\PayrollReport\App\Query\GetPayrollReport;
use App\PayrollReport\Domain\EmployeeReportFactory;
use App\PayrollReport\Domain\Exception\SalaryBonusTypeNotSupported;
use App\PayrollReport\Domain\Query\PayrollReportQueryParams;
use App\PayrollReport\Infra\Doctrine\Query\DBALGetPayrollReportHandler;
use App\PayrollReport\Infra\Doctrine\Query\Exception\FilterFieldNotSupportedException;
use App\PayrollReport\Infra\Doctrine\Query\Exception\OrderFieldNotSupportedException;
use App\PayrollReport\Infra\Doctrine\Query\QueryFieldMap;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DBALGetPayrollReportHandlerTest extends WebTestCase
{
    /**
     * @throws SalaryBonusTypeNotSupported
     * @throws FilterFieldNotSupportedException
     * @throws OrderFieldNotSupportedException
     * @throws Exception
     */
    public function testShouldReturnEmptyEmployeeReports(): void
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get('doctrine.orm.entity_manager');

        /** @var EmployeeReportFactory $employeeReportFactory */
        $employeeReportFactory = self::getContainer()->get('App\PayrollReport\Domain\EmployeeReportFactory');

        /** @var QueryFieldMap $queryFieldMap */
        $queryFieldMap = self::getContainer()->get('App\PayrollReport\Infra\Doctrine\Query\QueryFieldMap');

        $handler = new DBALGetPayrollReportHandler(
            $em,
            $employeeReportFactory,
            $queryFieldMap,
        );

        $result = $handler(
            new GetPayrollReport(
                new PayrollReportQueryParams(2023, 5)
           )
        );
        self::assertEquals([], $result->getEmployeeReports());
    }

    public function testShouldReturnEmployeeReports(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testOrderByFields(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testOrderByDirections(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testFilterByEmployeeSurname(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testFilterByEmployeeName(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testFilterByDepartmentName(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}