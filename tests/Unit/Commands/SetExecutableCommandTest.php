<?php

namespace Commands;

use PHPUnit\Framework\TestCase;
use Protoqol\Quark\Config\Commands\SetExecutableCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SetExecutableCommandTest extends TestCase
{

    private $application;
    private $command;

    protected function setUp()
    {
        $this->application = new Application();
        $this->application->addCommands([
            (new SetExecutableCommand()),
        ]);

        $this->command = $this->application->find('install');

    }

    public function testExecute()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([
            'command' => $this->command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Your Quark executable is ready!', $output);
    }
}
