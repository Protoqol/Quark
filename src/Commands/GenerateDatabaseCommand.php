<?php

namespace Protoqol\Quark\Commands;

use Protoqol\Quark\Quark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDatabaseCommand extends Command
{

    private static $database = 'database';
    private static $table    = 'table';

    protected static $defaultName = 'create';

    protected function configure()
    {
        $this->setDescription('Initialise an empty Quark database with an optional table inserted.')
             ->setHelp('quark initialise')
             ->setAliases(['quark:initialise'])
             ->addArgument(
                 'type',
                 InputArgument::REQUIRED,
                 'Database or Table'
             )->addArgument(
                'name',
                InputArgument::IS_ARRAY,
                'Table name'
            );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|string|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = strtolower($input->getArgument('type'));

        switch ($type) {
            case ($type === self::$database):
                $output->writeln(Quark::styleWriteLn((new Quark(getcwd()))->createDatabase($input->getArgument('name'))));
                break;
            case ($type === self::$table):
                $output->writeln('Table created... @TODO');
                break;
            default:
                $output->writeln('Type does not exist chose between \'database\' or \'table\'');
        }

    }

}
