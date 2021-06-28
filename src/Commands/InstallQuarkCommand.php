<?php

namespace Protoqol\Quark\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function quark;

class InstallQuarkCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'init';

    /**
     * @var array|string[]
     */
    private $directoriesNeeded = [
        'migrations',
        'tables',
        '_meta'
    ];

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Initialise Quark directory structure and config file.')
            ->addArgument('persist', InputArgument::OPTIONAL, 'Persist data to git.', false)
            ->setHelp('./quark init');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $quarkPath = env('QUARK_DIRECTORY', '/database/quark/');

        foreach ($this->directoriesNeeded as $dir) {
            $absolutePath = $GLOBALS['ROOT_DIR'] . $quarkPath . $dir;

            quark()->fs->mkdir($absolutePath);

            if (!$input->getArgument('persist')) {
                quark()->fs->touch($absolutePath . '/.gitignore');
            }

            quark()->fs->touch($absolutePath . '/.gitkeep');
            file_put_contents($absolutePath . '/.gitignore', '*.qrk');
        }

        $output->writeln(stylisedWriteLnOutput('Generated directory structure for Quark.'));

        return 1;
    }
}
