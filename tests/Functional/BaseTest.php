<?php

declare(strict_types=1);

namespace TestsFunctional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use function array_merge;

abstract class BaseTest extends WebTestCase
{
    protected function executeCommand(string $commandName, array $arguments = []): string
    {
        $application   = new Application(self::$kernel);
        $command       = $application->find($commandName);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array_merge(['command' => $commandName], $arguments));

        return $commandTester->getDisplay();
    }
}