<?php

declare(strict_types=1);

namespace App\PayrollReport\Infra\Doctrine\Query;

use App\PayrollReport\App\Query\GetPayrollReport;
use App\PayrollReport\Domain\EmployeeReportFactory;
use App\PayrollReport\Domain\Exception\SalaryBonusTypeNotSupported;
use App\PayrollReport\Domain\Query\OrderBy;
use App\PayrollReport\Domain\Query\OrderDirection;
use App\PayrollReport\Domain\Query\OrderField;
use App\PayrollReport\Domain\ValueObject\EmployeeReport;
use App\PayrollReport\Domain\ValueObject\EmployeeReportDTO;
use App\PayrollReport\Domain\ValueObject\PayrollReport;
use App\PayrollReport\Infra\Doctrine\Query\Exception\FilterFieldNotSupportedException;
use App\PayrollReport\Infra\Doctrine\Query\Exception\OrderFieldNotSupportedException;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler(bus: 'query.bus')]
class DBALGetPayrollReportHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EmployeeReportFactory $employeeReportFactory,
        private readonly QueryFieldMap $fieldMap,
    ) {
    }

    /**
     * @throws SalaryBonusTypeNotSupported
     * @throws Exception
     * @throws FilterFieldNotSupportedException
     * @throws OrderFieldNotSupportedException
     * @throws \Exception
     */
    public function __invoke(GetPayrollReport $query): PayrollReport
    {
        $queryParams = $query->getParams();

        $reportDate = new DateTimeImmutable();
        $reportDate = $reportDate->setDate($queryParams->getYear(), $queryParams->getMonth(), 1);

        $qb = $this->em->getConnection()->createQueryBuilder();

        /** @psalm-suppress TooManyArguments */
        $qb->select(
            'e.name as employee_name',
            'e.surname as employee_surname',
            'e.employment_date',
            'e.monthly_salary',
            'd.name as department_name',
            'd.salary_bonus_type',
            'd.salary_bonus_value',
        )
        ->from('employees', 'e')
        ->leftJoin('e', 'departments', 'd', 'e.department_id = d.id')
        ->andWhere('e.employment_date <= :reportDate')
        ->andWhere('e.termination_date >= :reportDate OR e.termination_date IS NULL')
        ->setParameter('reportDate', $reportDate, Types::DATE_IMMUTABLE);



        foreach ($queryParams->getFilters() as $filter) {
            $qb->andWhere(
                sprintf('%s = :%s', $this->fieldMap->getFilterFieldName($filter->field), $filter->field->value)
            );
            $qb->setParameter($filter->field->value, $filter->value);
        }

        if (
            ($orderBy = $queryParams->getOrderBy())
            && $this->fieldMap->isOrderFieldMapped($orderBy->orderField)
        ) {
            $qb->orderBy(
                $this->fieldMap->getOrderFieldName($orderBy->orderField),
                $orderBy->orderDirection->value
            );
        }

        $results = $qb->fetchAllAssociative();

        $employeeReports = array_map(
            function (array $row): EmployeeReport {
                return $this->employeeReportFactory->create(
                    new EmployeeReportDTO(
                        SalaryBonusType::from((int)$row['salary_bonus_type']),
                        (float)$row['salary_bonus_value'],
                        (float)$row['monthly_salary'],
                        new DateTimeImmutable((string)$row['employment_date']),
                        (string)$row['employee_name'],
                        (string)$row['employee_surname'],
                        (string)$row['department_name'],
                    )
                );
            },
            $results
        );

        if (($orderBy = $queryParams->getOrderBy())
            && !$this->fieldMap->isOrderFieldMapped($orderBy->orderField)
        ) {
            $employeeReports = $this->sortEmployeeReports($employeeReports, $orderBy);
        }

        return new PayrollReport($queryParams->getYear(), $queryParams->getMonth(), $employeeReports);
    }

    /**
     * @param EmployeeReport[] $employeeReports
     * @return EmployeeReport[]
     * @throws OrderFieldNotSupportedException
     * todo: move to some other place
     */
    private function sortEmployeeReports(array $employeeReports, OrderBy $orderBy): array
    {
        if ($orderBy->orderField === OrderField::BONUS_AMOUNT) {
            if ($orderBy->orderDirection === OrderDirection::ASC) {
                usort(
                    $employeeReports,
                    static function (EmployeeReport $a, EmployeeReport $b): int {
                        return (int)($a->getSalary()->getBonusAmount() - $b->getSalary()->getBonusAmount());
                    }
                );
            } else {
                usort(
                    $employeeReports,
                    static function (EmployeeReport $a, EmployeeReport $b): int {
                        return (int)($b->getSalary()->getBonusAmount() - $a->getSalary()->getBonusAmount());
                    }
                );
            }
        }
        else if ($orderBy->orderField === OrderField::SALARY_TOTAL) {
            if ($orderBy->orderDirection === OrderDirection::ASC) {
                usort(
                    $employeeReports,
                    static function (EmployeeReport $a, EmployeeReport $b): int {
                        return (int)($a->getSalary()->getTotal() - $b->getSalary()->getTotal());
                    }
                );
            } else {
                usort(
                    $employeeReports,
                    static function (EmployeeReport $a, EmployeeReport $b): int {
                        return (int)($b->getSalary()->getTotal() - $a->getSalary()->getTotal());
                    }
                );
            }
        } else {
            throw new OrderFieldNotSupportedException();
        }

        return $employeeReports;
    }
}