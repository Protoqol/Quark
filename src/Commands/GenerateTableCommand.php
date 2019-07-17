<?php

namespace Protoqol\Quark\Commands;

use Symfony\Component\Console\Command\Command;

class GenerateTableCommand extends Command
{

    protected static $defaultName = 'quark:create-table';

    protected function configure()
    {
            $this->setDescription('Create a new table in your database.');
    }
}
