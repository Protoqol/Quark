<?php

namespace Protoqol\Quark\Commands;

use Protoqol\Quark\Database\Migrations;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMigrationCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'create';

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Create a new Quark migration.');
        $this->setHelp('php quark create {TableName}');
        $this->addArgument('name', InputArgument::REQUIRED, 'TableName');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tableName = $input->getArgument('name');

        $migration = (new Migrations)->generate($tableName);

        $msg = $migration ? "Successfully created migration for " . $tableName : "An unexpected error occurred.";

        $output->writeln(stylisedWriteLnOutput($msg));

        return 1;
    }
}
