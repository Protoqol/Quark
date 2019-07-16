<?php

namespace Protoqol\Quark\Commands;

use Quark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveExecutableCommand extends Command
{

    protected static $defaultName = 'quark:remove';

    protected function configure()
    {
        $this->setDescription('Remove Quark executable from project root.');
        $this->setHelp('This command removes the Quark executable in the project\'s root directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return (new Quark(getcwd()))->setExecutable();
    }
}
