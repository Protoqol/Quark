<?php

namespace Protoqol\Quark\Commands;

use Carbon\Carbon;
use Protoqol\Quark\Database\Migrations;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunMigrationsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'migrate';

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Run Quark migrations.');
        $this->addOption('dry-run', 'D', InputOption::VALUE_OPTIONAL, "Dry run migrations, meaning the migrations will not be persisted to the database.", false);
        $this->setHelp('php quark migrate');
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
        $migrations = new Migrations($input->getOption('dry-run'));

        if (!$migrations->pendingMigrations) {
            $output->writeln(
                stylisedWriteLnOutput("There are no pending migrations!")
            );

            $time = Carbon::parse($migrations->latestMigration['migrated_at'])->format('m-d-Y H:i:s');

            $output->writeln(
                stylisedWriteLnOutput("Latest migration was \"{$migrations->latestMigration['name']}\" on {$time}", 'yellow')
            );

            return 1;
        }

        $output->writeln(
            stylisedWriteLnOutput("Migrating {$migrations->pendingMigrations} migrations")
        );

        foreach ($migrations->run() as $value) {
            $output->writeln(
                stylisedWriteLnOutput("- Generating table for {$value}" . ($input->getOption('dry-run') ? " - (dry)" : ''), 'yellow')
            );
        }

        $output->writeln(
            stylisedWriteLnOutput("Successfully processed all migrations.")
        );

        return 1;
    }
}
