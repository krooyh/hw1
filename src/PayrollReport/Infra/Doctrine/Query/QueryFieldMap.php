<?php

declare(strict_types=1);

namespace App\PayrollReport\Infra\Doctrine\Query;

use App\PayrollReport\Domain\Query\FilterField;
use App\PayrollReport\Domain\Query\OrderField;
use App\PayrollReport\Infra\Doctrine\Query\Exception\FilterFieldNotSupportedException;
use App\PayrollReport\Infra\Doctrine\Query\Exception\OrderFieldNotSupportedException;
use UnhandledMatchError;

class QueryFieldMap
{
    /**
     * @throws FilterFieldNotSupportedException
     */
    public function getFilterFieldName(FilterField $filterField): string
    {
        try {
            return match ($filterField) {
                FilterField::DEPARTMENT_NAME => 'd.name',
                FilterField::EMPLOYEE_NAME => 'e.name',
                FilterField::EMPLOYEE_SURNAME => 'e.surname',
            };
        } catch (UnhandledMatchError $e) {
            throw new FilterFieldNotSupportedException();
        }
    }

    /**
     * @throws OrderFieldNotSupportedException
     */
    public function getOrderFieldName(OrderField $orderField): string
    {
        try {
            /** @psalm-suppress UnhandledMatchCondition */
            return match ($orderField) {
                OrderField::DEPARTMENT_NAME => 'd.name',
                OrderField::BONUS_TYPE => 'd.salary_bonus_type',
                OrderField::MONTHLY_SALARY => 'e.monthly_salary',
                OrderField::EMPLOYEE_NAME => 'e.name',
                OrderField::EMPLOYEE_SURNAME => 'e.surname',
            };
        } catch (UnhandledMatchError $e) {
            throw new OrderFieldNotSupportedException();
        }
    }

    public function isOrderFieldMapped(OrderField $orderField): bool
    {
        try {
            $this->getOrderFieldName($orderField);
        } catch (OrderFieldNotSupportedException $e) {
            return false;
        }

        return true;
    }

}