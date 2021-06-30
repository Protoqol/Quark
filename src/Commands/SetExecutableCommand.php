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
        $this->setDescription('Install a Quark executable in your project\'s root directory.');
        $this->setHelp('This command creates a Quark executable in the project\'s root directory as a convenience.');
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
            $res = false;

            $dev_origin = quark()->cwd . '/bin/quark';

            $origin = quark()->cwd . '/vendor/protoqol/quark/bin/quark';
            $target = quark()->cwd . '/quark';

            // Copy .quark-env-example to root.

            if (quark()->fs->exists($origin)) {
                quark()->fs->copy($origin, $target, true);
                quark()->fs->chmod($target, 0755);

                $res = quark()->fs->exists($target);
            }

            if (quark()->fs->exists($dev_origin)) {
                quark()->fs->copy($dev_origin, $target, true);
                quark()->fs->chmod($target, 0755);

                $res = quark()->fs->exists($target);
            }

            if ($res) {
                $output->writeln(
                    stylisedWriteLnOutput("Your Quark executable is ready! You can now run `./quark` (or `php quark`) to interact with Quark!")
                );

                return 1;
            }

            return 0;
        } catch (\Exception $e) {
            $output->writeln('Something went wrong while trying to set the Quark executable...');

            return 0;
        }
    }
}
