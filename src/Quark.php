<?php

namespace Protoqol\Quark;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Quark
 */
class Quark
{

    /**
     * FileSystem instance
     * @var Filesystem $fs
     */
    private $fs;

    /**
     * Current working directory
     * @var string $cwd
     */
    private $cwd;

    /**
     * Default Quark directory name. Used as overhead for Quark activity.
     * @var string $defaultQuarkDirectoryName
     */
    private $defaultQuarkDirectoryName = 'quark/';

    /**
     * Default database directory. @TODO possibly get from config.
     * @var string $defaultDirectory
     */
    private $defaultDirectory = 'database/';

    /**
     * Holds default database file name. @TODO Possibly via config.
     * @var string $defaultFileName
     */
    private $defaultFileName = 'database.qrk';

    /**
     * Holds array of strings of possible database directories in a project.
     * @var array $standardDatabaseDirectories
     */
    private $standardDatabaseDirectories = [
        'DB',
        'db',
    ];

    /**
     * Force overwrite.
     * @var bool $mock
     */
    private $force;

    public function __construct(string $cwd, bool $force = false)
    {
        $this->fs    = new Filesystem();
        $this->cwd   = $cwd;
        $this->force = $force;
    }

    /**
     * Set a Quark executable in root directory.
     * @return bool
     */
    public function setExecutable(): bool
    {
        $dev_origin = $this->cwd . '/bin/quark';

        $origin = $this->cwd . '/vendor/protoqol/quark/bin/quark';
        $target = $this->cwd . '/quark';

        if ($this->fs->exists($origin)) {
            $this->fs->copy($origin, $target, true);
            $this->fs->chmod($target, 0755);

            return (bool)$this->fs->exists($target);
        }

        if ($this->fs->exists($dev_origin)) {
            $this->fs->copy($dev_origin, $target, true);
            $this->fs->chmod($target, 0755);

            return (bool)$this->fs->exists($target);
        }

        return false;
    }

    /**
     * Initialise database file in ./database/database.qrk
     *
     * @param string|null $table
     *
     * @return bool|string
     */
    public function createDatabase(?string $table = '')
    {
        // Create quark directory.
        $file = $this->createResidingDatabaseDirectory() . $this->defaultFileName;

        // Create database.qrk
        $this->fs->touch($file);


        // If table name is specified, create table in database.
        $tableMsg = '';
        if ($table && strlen($table) > 0) {
            $this->fs->appendToFile($file, (new Table())->generateTable($table));
            $tableMsg = " with table '{$table}'";
        }

        if ($this->fs->exists($file)) {
            return "New database" . $tableMsg . " created at: " . $this->cwd . '/' . $file;
        }

        return false;
    }

    /**
     * Check and create the residing directory for Quark and create database.qrk.
     * @return string
     */
    private function createResidingDatabaseDirectory(): string
    {
        if ($this->fs->exists('db')) {
            $folder = './db/' . $this->defaultQuarkDirectoryName;
            $this->fs->mkdir($folder);

            if ($this->fs->exists($folder)) {
                return $folder;
            }
        }

        if ($this->fs->exists('DB')) {
            $folder = './DB/' . $this->defaultQuarkDirectoryName;
            $this->fs->mkdir($folder);

            if ($this->fs->exists($folder)) {
                return $folder;
            }
        }

        if (!$this->fs->exists($this->standardDatabaseDirectories)) {
            $folder = $this->defaultDirectory . $this->defaultQuarkDirectoryName;
            $this->fs->mkdir($folder);

            if ($this->fs->exists($folder)) {
                return $folder;
            }
        }

        return false;
    }

    public function checkForExistingDatabase()
    {
        if ($this->fs->exists()) {
            //
        }
    }

    /**
     * Generate command in Quark's style.
     *
     * @param string $output
     *
     * @return array
     */
    public static function styleWriteLn(string $output)
    {
        return [
            '<options=bold;fg=white;bg=magenta>QUARK says...</>',
            '<options=bold;fg=green;>' . $output . '</>',
            '<fg=white>end of message</>',
        ];
    }
}
