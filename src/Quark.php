<?php

namespace Protoqol\Quark;

use Protoqol\Quark\Connection\DatabaseAccessor;
use Protoqol\Quark\IO\Reader;
use Protoqol\Quark\IO\Table;
use Protoqol\Quark\IO\Writer;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Quark
 */
class Quark
{
    /**
     * Field name for meta data
     */
    public const META_ACCESSOR = '__';

    /**
     * FileSystem instance
     *
     * @var Filesystem $fs
     */
    public $fs;

    /**
     * Current working directory
     *
     * @var string $cwd
     */
    public $cwd;

    /**
     * @var Reader
     */
    public $reader;

    /**
     * @var Writer
     */
    public $writer;

    /**
     * Quark constructor.
     *
     * @param string|null $cwd
     */
    public function __construct(string $cwd = NULL)
    {
        $this->fs = new Filesystem();
        $this->writer = new Writer();
        $this->reader = new Reader();
        $this->cwd = $cwd ?? $GLOBALS['ROOT_DIR'];
    }

    /**
     * Write data to file.
     *
     * @param string $absolutePath
     * @param mixed  $data
     * @param bool   $json_encoded
     *
     * @return false|int
     */
    public function write(string $absolutePath, $data, bool $json_encoded = false)
    {
        return $this->writer->write($absolutePath, $data, $json_encoded);
    }

    /**
     * Read data from file.
     *
     * @param string $absolutePath
     * @param bool   $json_decode
     * @param bool   $resulting If the file is a PHP script this parameter will return the output instead of the content.
     *
     * @return string|array
     */
    public function read(string $absolutePath, bool $json_decode = false, bool $resulting = false)
    {
        return $this->reader->read($absolutePath, $resulting, $json_decode);
    }

    /**
     * Create file if it does not exist already.
     *
     * @param string $absolutePath
     *
     * @return bool
     */
    public function createIfAbsent(string $absolutePath): bool
    {
        if (!$this->fs->exists($absolutePath)) {
            $this->fs->touch($absolutePath);

            $this->createIfAbsent($absolutePath);
        }

        return true;
    }

    /**
     * @param string|null $customFile
     *
     * @return DatabaseAccessor
     */
    public function connection(string $customFile = NULL): DatabaseAccessor
    {
        return (new DatabaseAccessor($customFile));
    }

    /**
     * Set a Quark executable in root directory.
     *
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

            return $this->fs->exists($target);
        }

        if ($this->fs->exists($dev_origin)) {
            $this->fs->copy($dev_origin, $target, true);
            $this->fs->chmod($target, 0755);

            return $this->fs->exists($target);
        }

        return false;
    }

    /**
     * Initialise database file in configured directory.
     *
     * @param string|null $table
     *
     * @return bool|string
     */
    public function createDatabase(?string $table = '')
    {
        #$file = $this->createResidingDatabaseDirectory() . $this->defaultFileName;
        $this->fs->touch($file);

        // If table name is specified, create table in database.
        $tableMsg = '';
        if ($table && $table !== '') {
            $this->fs->appendToFile($file, (new Table())->generateTable($table));
            $tableMsg = " with table '{$table}'";
        }

        if ($this->fs->exists($file)) {
            return "New database" . $tableMsg . " created at: " . $this->cwd . '/' . $file;
        }

        return false;
    }

    /**
     * Get absolute path for application directory (users' project).
     *
     * @param string|null $path
     *
     * @return string
     */
    public function rootPath(string $path = null): string
    {
        return $GLOBALS['ROOT_DIR'] . $this->formatPath($path) ?: '';
    }

    /**
     * Get absolute path for quark directory.
     *
     * @param string|null $path
     *
     * @return string
     */
    public function quarkPath(string $path = null): string
    {
        return $this->rootPath(env('QUARK_DIRECTORY', '/database/quark/') . $this->formatPath($path) ?: '');
    }

    /**
     * Get absolute path for tables directory.
     *
     * @param string|null $path
     *
     * @return string
     */
    public function tablesPath(string $path = null): string
    {
        return $this->quarkPath() . 'tables' . $this->formatPath($path) ?: '';
    }

    /**
     * Get absolute path for migrations directory.
     *
     * @param string|null $path
     *
     * @return string
     */
    public function migrationsPath(string $path = null): string
    {
        return $this->quarkPath() . 'migrations' . $this->formatPath($path) ?: '';
    }

    /**
     * Get absolute path for meta directory.
     *
     * @param string|null $path
     *
     * @return string
     */
    public function metaPath(string $path = null): string
    {
        return $this->quarkPath() . '_meta' . $this->formatPath($path) ?: '';
    }

    /**
     * Determine if path needs an addition separator.
     *
     * @param string|null $path
     *
     * @return string|null
     */
    private function formatPath(string $path = null): ?string
    {
        if ($path === null) {
            return null;
        }

        if ($path[0] === DIRECTORY_SEPARATOR) {
            return $path;
        }

        return DIRECTORY_SEPARATOR . $path;
    }
}
