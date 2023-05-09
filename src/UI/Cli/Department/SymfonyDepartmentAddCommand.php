<?php

declare(strict_types=1);

namespace App\UI\Cli\Department;

use App\Department\App\Command\AddDepartment;
use App\Shared\Domain\ValueObject\SalaryBonusType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Ulid;

#[AsCommand(
    name: 'app:department:add',
    description: 'Add new department',
)]
class SymfonyDepartmentAddCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Department name')
            ->addArgument('salaryBonusType', InputArgument::REQUIRED, 'Salary bonus type [FIXED => 0, PERCENTAGE => 1] integer')
            ->addArgument('salaryBonusValue', InputArgument::REQUIRED, 'Salary bonus value [0.00 -> 999999.99 ]')
        ;
    }

    /**
     * @throws \Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = (string)$input->getArgument('name');
        $salaryBonusType = (int)$input->getArgument('salaryBonusType');
        $salaryBonusValue = (float)$input->getArgument('salaryBonusValue');

        $id = new Ulid();

        try {
            $this->commandBus->dispatch(
                new AddDepartment(
                    $id,
                    $name,
                    SalaryBonusType::from($salaryBonusType),
                    $salaryBonusValue,
                )
            );
        } catch (ValidationFailedException $exception) {
            $violations = $exception->getViolations();
            foreach ($violations as $violation) {
                $io->error(sprintf('%s, %s', $violation->getPropertyPath(), (string)$violation->getMessage()));
            }
        } catch (HandlerFailedException $exception) {
            $e = $exception->getPrevious();
            while ($e instanceof HandlerFailedException) {
                /** @var \Throwable $e */
                $e = $e->getPrevious();
            }

            $e instanceof \Throwable
                ? throw $e
                : throw $exception;
        }


        $io->note(sprintf('New department with id %s added.', $id->toBase32()));

        return Command::SUCCESS;
    }
}
