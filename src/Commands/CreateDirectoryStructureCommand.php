<?php

namespace Protoqol\Quark\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function quark;

class CreateDirectoryStructureCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'initialise';

    /**
     * @var array|string[]
     */
    protected static $directoriesNeeded = [
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
        $this->setDescription('Initialise Quark directory structure and config file.');
        $this->setAliases(['init']);
        $this->addOption('omit-gitignore', 'Og', InputOption::VALUE_OPTIONAL, 'Omit .gitignore file for Quark files.', false);
        $this->setHelp('php quark init');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            foreach (self::$directoriesNeeded as $dir) {
                $absolutePath = quark()->quarkPath($dir);

                if ($dir === '_meta') {
                    $internalConfigPath = $absolutePath . DIRECTORY_SEPARATOR . 'internal_config.qrk';

                    if (quark()->createIfAbsent($internalConfigPath)) {
                        quark()->write(
                            $internalConfigPath,
                            [
                            'root_dir' => quark()->cwd
                            ],
                            true
                        );
                    }
                }

                quark()->fs->mkdir($absolutePath);

                if (!$input->getOption('omit-gitignore')) {
                    quark()->fs->appendToFile($absolutePath . '/.gitignore', '*.qrk');
                }

                quark()->fs->touch($absolutePath . '/.gitkeep');
            }

            $output->writeln(
                stylisedWriteLnOutput('Generated directory structure for Quark.')
            );
            return 1;
        } catch (\Exception $e) {
            $output->writeln(
                stylisedWriteLnOutput('Oops... Something went wrong while generating directory structure.', 'red')
            );

            if ($output->getVerbosity() === OutputInterface::VERBOSITY_DEBUG) {
                $output->write($e);
            }
            return 0;
        }
    }
}
