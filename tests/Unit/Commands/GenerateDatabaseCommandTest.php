<?php

namespace Commands;

use PHPUnit\Framework\TestCase;
use Protoqol\Quark\Config\Commands\GenerateDatabaseCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Test for quark:init command and Quark::class
 * @package Protoqol\Prequel\Tests\Unit
 */
class GenerateDatabaseCommandTest extends TestCase
{

    private $application;

    private $command;

    private $commandTest;

    protected function setUp()
    {
        $this->application = new Application();
        $this->application->addCommands([
            (new GenerateDatabaseCommand()),
        ]);

        $this->command     = $this->application->find('quark:init');
        $this->commandTest = new CommandTester($this->command);
    }

    public function testSuccessfulDatabaseCreation()
    {
        $this->commandTest->execute([
            'command' => $this->command->getName(),
            'type'    => 'database',
            'name'    => 'test',
        ]);

        $output = $this->commandTest->getDisplay();

        // Assertions
        $this->assertContains('New database with table \'test\' created at:', $output);
        $this->assertTrue((new Filesystem())->exists('database/quark/database.qrk'));
    }

    public function testFaultyDatabaseCreation()
    {
        $this->commandTest->execute([
            'command' => $this->command->getName(),
            'type'    => 'TEST_NON_EXISTENT_TYPE',
        ]);

        $output = $this->commandTest->getDisplay();

        $this->assertContains('Type does not exist', $output);
    }
}
