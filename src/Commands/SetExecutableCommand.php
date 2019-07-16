<?php

namespace Protoqol\Quark\Commands;

use Quark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetExecutableCommand extends Command
{

    protected static $defaultName = 'quark:publish';

    protected function configure()
    {
        $this->setDescription('Get a Quark executable in your project root directory.');
        $this->setHelp('This command creates a Quark executable in the project\'s root directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $res = (new Quark(getcwd()))->setExecutable();

        if ($res) {
            $output->writeln([
                ' ',
                'Your Quark executable is ready! You can now run ./quark to execute commands!',
                ' ',
            ]);

            return true;
        }

        $output->writeln('Something went wrong while trying to set the Quark executable...');

        return true;

    }
}
