<?php

namespace Protoqol\Quark\Commands;

use Protoqol\Quark\Quark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetExecutableCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'install';

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setAliases(['quark:install']);
        $this->setDescription('Install a Quark executable in your project\'s root directory.');
        $this->setHelp('This command creates a Quark executable in the project\'s root directory as a convenience.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output): bool
    {
        $res = (new Quark($GLOBALS['ROOT_DIR']))->setExecutable();

        if ($res) {
            $output->writeln([
                ' ',
                'Your Quark executable is ready! You can now run `./quark` (or `php quark`) to execute commands!',
                ' ',
            ]);

            return true;
        }

        $output->writeln('Something went wrong while trying to set the Quark executable...');

        return true;

    }
}
