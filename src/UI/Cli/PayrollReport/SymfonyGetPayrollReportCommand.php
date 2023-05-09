<?php

declare(strict_types=1);

namespace App\UI\Cli\PayrollReport;

use App\PayrollReport\App\Query\GetPayrollReport;
use App\PayrollReport\Domain\Query\Filter;
use App\PayrollReport\Domain\Query\FilterField;
use App\PayrollReport\Domain\Query\OrderBy;
use App\PayrollReport\Domain\Query\OrderDirection;
use App\PayrollReport\Domain\Query\OrderField;
use App\PayrollReport\Domain\Query\PayrollReportQueryParams;
use App\PayrollReport\Domain\ValueObject\PayrollReport;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[AsCommand(
    name: 'app:payroll_report:get',
    description: 'Get payroll report monthly',
)]
class SymfonyGetPayrollReportCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $queryBus,
        private readonly SymfonyCommandTableFormatter $reportFormatter,
        private readonly ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('year', null, InputOption::VALUE_REQUIRED, 'Year of the report')
            ->addOption('month', null, InputOption::VALUE_REQUIRED, 'Month of the report')
            ->addOption(
                'order_by_field',
               null,
                InputOption::VALUE_OPTIONAL,
                sprintf('Order by field [%s]', implode(', ', array_column(OrderField::cases(), 'value'))),
            )
            ->addOption(
                'order_by_direction',
                null,
                InputOption::VALUE_OPTIONAL,
                'Order by direction [ASC, DESC]',
                OrderDirection::ASC->value
            )
            ->addOption(
                'filter_by_department_name',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter by department name',
            )
            ->addOption(
                'filter_by_employee_name',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter by employee name',
            )
            ->addOption(
                'filter_by_employee_surname',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter by employee surname',
            )
        ;
    }

    /**
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $payrollParams = $this->createPayrollParams(
            (int)$input->getOption('year'),
            (int)$input->getOption('month'),
            (string)$input->getOption('order_by_field'),
            (string)$input->getOption('order_by_direction'),
            (string)$input->getOption('filter_by_department_name'),
            (string)$input->getOption('filter_by_employee_name'),
            (string)$input->getOption('filter_by_employee_surname'),
        );

        $this->validatePayrollParams($payrollParams);

        try {
            $envelope = $this->queryBus->dispatch(new GetPayrollReport($payrollParams));
        } catch (HandlerFailedException $exception) {
            $e = $exception->getPrevious();
            while ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
            }

            $e instanceof Throwable ? throw $e : throw $exception;
        }

        $handledStamp = $envelope->last(HandledStamp::class);

        if (null !== $handledStamp) {
            /** @psalm-suppress MixedAssignment */
            $results = $handledStamp->getResult();
            if ($results instanceof PayrollReport && [] !== $results->getEmployeeReports()) {
                $io->table(
                    $this->reportFormatter->getHeaders(),
                    $this->reportFormatter->getRows($results),
                );
            } else {
                $io->note('No reports was found.');
            }

            return Command::SUCCESS;
        }

        $io->error('something went wrong');
        return Command::FAILURE;
    }

    private function validatePayrollParams(PayrollReportQueryParams $payrollParams): void
    {
        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($payrollParams);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            throw new InvalidArgumentException($errorsString);
        }
    }

    private function createPayrollParams(
        int $year,
        int $month,
        string $orderByField,
        string $orderByDirection,
        string $filterByDepartmentName,
        string $filterByEmployeeName,
        string $filterByEmployeeSurname
    ): PayrollReportQueryParams {
        $payrollParams = new PayrollReportQueryParams(
            $year,
            $month,
            $orderByField
                ? new OrderBy(OrderField::from($orderByField), OrderDirection::from($orderByDirection))
                : null,
        );

        if ($filterByDepartmentName) {
            $payrollParams->addFilter(new Filter(FilterField::DEPARTMENT_NAME, $filterByDepartmentName));
        }

        if ($filterByEmployeeName) {
            $payrollParams->addFilter(new Filter(FilterField::EMPLOYEE_NAME, $filterByEmployeeName));
        }

        if ($filterByEmployeeSurname) {
            $payrollParams->addFilter(new Filter(FilterField::EMPLOYEE_SURNAME, $filterByEmployeeSurname));
        }

        return $payrollParams;
    }
}
