<?php

namespace Protoqol\Quark\Commands;

use Exception;
use Protoqol\Quark\Config;
use Protoqol\Quark\Quark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDatabaseCommand extends Command
{

    /**
     * @var string $database
     */
    private static string $database = 'database';

    /**
     * @var string $table
     */
    private static string $table = 'table';

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
        $this->setDescription('Create a new Quark database with an optional table inserted already appended.')
            ->setHelp('./quark create database|table {name}')
            ->setAliases([
                'new',
                'make',
            ])
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'database (db) or table (tb)'
            )->addArgument(
                'name',
                InputArgument::IS_ARRAY,
                'Table name(s)'
            );
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
        $type = strtolower($input->getArgument('type'));

        switch ($type) {
            case 'tb':
                $type = 'table';
                break;
            case 'db':
                $type = 'database';
                break;
            default:
                break;
        }

        switch ($type) {
            case ($type === self::$database):
                foreach ($input->getArgument('name') as $database) {
                    $output->writeln(Quark::styleWriteLn((new Quark(getcwd()))->createDatabase($database)));
                }
                break;
            case ($type === self::$table):
                $output->writeln('Table created... @TODO');
                break;
            default:
                $output->writeln('Type does not exist, choose between \'database\' or \'table\'');
        }

    }

}
