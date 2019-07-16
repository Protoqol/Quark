<?php

namespace Protoqol\Quark\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDatabaseCommand extends Command
{

    protected static $defaultName = 'quark:init';

    protected function configure()
    {
        // File name/location/preset?
        $this->addArgument('directory');

        $this->setDescription('Install Quark database in specified directory.')
             ->setHelp('quark initialise [tablename]');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|string|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo 'Success';

        return parent::execute($input, $output);
    }

}
