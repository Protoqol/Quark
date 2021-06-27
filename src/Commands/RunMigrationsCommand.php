<?php

namespace Protoqol\Quark\Commands;

use Protoqol\Quark\Database\Migration;
use Protoqol\Quark\Quark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
        $this->setDescription('Run Quark migrations.')
            ->setHelp('./quark migrate');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        (new Migration)->runMigrationFiles();

        $msg = "Successfully ran migrations";

        $output->writeln(Quark::styleWriteLn($msg));
    }
}
