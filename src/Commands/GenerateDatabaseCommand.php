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
        $this->setDescription('Create a new Quark database with an optional table inserted already appended.')
             ->setHelp('./quark create database|table ...name(s)')
             ->setAliases([
                 'new',
                 'make'
             ])
             ->addArgument(
                 'type',
                 InputArgument::REQUIRED,
                 'Database (database|db) or Table (table|tb)'
             )->addArgument(
                'name',
                InputArgument::IS_ARRAY,
                'Table name(s)'
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

        if($type === 'tb'){
            $type = 'table';
        }

        if($type === 'db'){
            $type = 'database';
        }

        switch ($type) {
            case ($type === self::$database):
                foreach($input->getArgument('name') as $database){
                    $output->writeln(Quark::styleWriteLn((new Quark(getcwd()))->createDatabase($database)));
                }
                break;
            case ($type === self::$table):
                $output->writeln('Table created... @TODO');
                break;
            default:
                $output->writeln('Type does not exist chose between \'database\' or \'table\'');
        }

    }

}
