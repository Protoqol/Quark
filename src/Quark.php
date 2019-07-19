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
     * @var Symfony\Component\Filesystem\Filesystem $fs
     */
    private $fs;

    /**
     * Current working directory
     * @var string $cwd
     */
    private $cwd;

    /**
     * Default Quark directory name. Used as overhead for Quark activity.
     * @var string
     */
    private $defaultQuarkDirectoryName = 'quark/';

    /**
     * Default database directory. @TODO possibly get from config.
     * @var string
     */
    private $defaultDirectory = 'database/';

    /**
     * Holds default database file name. @TODO Possibly via config.
     * @var string
     */
    private $defaultFileName = 'database.qrk';

    /**
     * Holds array of strings of possible database directories in a project.
     * @var array
     */
    private $standardDatabaseDirectories = [
        'DB',
        'db',
    ];

    public function __construct(string $cwd)
    {
        $this->fs  = new Filesystem();
        $this->cwd = $cwd;
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
        if ($table && strlen($table) > 0) {
            $this->fs->appendToFile($file, (new Table())->generateTable($table));
        }

        if ($this->fs->exists($file)) {
            return "Database created at: " . $this->cwd . '/' . $file;
        }

        return false;
    }

    /**
     * Check and create the residing directory for Quark and create database.qrk.
     * @return string
     */
    private function createResidingDatabaseDirectory(): string
    {
        $dir = 'database';

        if (!$this->fs->exists($this->standardDatabaseDirectories)) {
            $folder = $this->defaultDirectory . $this->defaultQuarkDirectoryName;
            $this->fs->mkdir($folder);
            $dir = $folder;
        }

        if ($this->fs->exists('db')) {
            $folder = './db/' . $this->defaultQuarkDirectoryName;
            $this->fs->mkdir($folder);
            $dir = $folder;
        }

        if ($this->fs->exists('DB')) {
            $folder = './DB/' . $this->defaultQuarkDirectoryName;
            $this->fs->mkdir($folder);
            $dir = $folder;
        }

        return $dir;
    }

    public function checkForExistingDatabase()
    {
        if($this->fs->exists())
    }
}
