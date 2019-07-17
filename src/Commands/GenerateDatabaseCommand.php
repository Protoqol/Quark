<?php

namespace Protoqol\Quark\Commands;

use Protoqol\Quark\Quark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDatabaseCommand extends Command
{

    protected static $defaultName = 'quark:init';

    protected function configure()
    {
        $this->setDescription('Initialise an empty Quark database with specified name.')
             ->setHelp('quark initialise')
             ->setAliases(['quark:initialise'])
             ->addArgument(
                 'table',
                 InputArgument::OPTIONAL,
                 'Append table in the created database.'
             );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|string|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        printf("\n");
        echo (new Quark(getcwd()))->createDatabase($input->getArgument('table'));
        printf("\n");
        return true;
    }

}
