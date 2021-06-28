<?php

namespace Protoqol\Quark\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetExecutableCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'install';

    /**
     * Configure command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setAliases(['quark:install']);
        $this->setDescription('Install a Quark executable in your project\'s root directory.');
        $this->setHelp('This command creates a Quark executable in the project\'s root directory as a convenience.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output): bool
    {
        $res = false;

        $dev_origin = $GLOBALS['ROOT_DIR'] . '/bin/quark';

        $origin = $GLOBALS['ROOT_DIR'] . '/vendor/protoqol/quark/bin/quark';
        $target = $GLOBALS['ROOT_DIR'] . '/quark';

        if (quark()->fs->exists($origin)) {
            quark()->fs->copy($origin, $target, true);
            quark()->fs->chmod($target, 0755);

            $res = (bool)quark()->exists($target);
        }

        if (quark()->fs->exists($dev_origin)) {
            quark()->fs->copy($dev_origin, $target, true);
            quark()->fs->chmod($target, 0755);

            $res = (bool)quark()->fs->exists($target);
        }

        if ($res) {
            $output->writeln([
                ' ',
                'Your Quark executable is ready! You can now run `./quark` (or `php quark`) to execute commands!',
                ' ',
            ]);

            return true;
        }

        $output->writeln('Something went wrong while trying to set the Quark executable...');

        return true;

    }
}
