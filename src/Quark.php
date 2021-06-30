<?php

namespace Protoqol\Quark;

use Dotenv\Dotenv;
use Protoqol\Quark\IO\Reader;
use Protoqol\Quark\IO\Writer;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Quark
 */
class Quark
{
    /**
     * FileSystem instance
     *
     * @var Filesystem $fs
     */
    public $fs;

    /**
     * Current working directory
     *
     * @TODO refactor to getCwd() method.
     *
     * @var string $cwd
     */
    public $cwd;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var Writer
     */
    protected $writer;

    /**
     * Quark constructor.
     *
     * @param string|null $cwd
     */
    public function __construct(string $cwd = null)
    {
        $this->fs = new Filesystem;
        $this->writer = new Writer;
        $this->reader = new Reader;

        // If called from CLI the cwd can be assumed to be the root directory.
        $this->cwd = $cwd ?: getcwd();
        // $this->cwd = $cwd ?? (defined('STDIN') ? getcwd() : $this->getMetaKey('root_dir'));
        // $this->env = Dotenv::createImmutable(getcwd() . DIRECTORY_SEPARATOR, 'quark-env');
        // $this->env->load();
        // dd($_ENV['QUARK_DIRECTORY']);
    }

    /**
     * Write data to file.
     *
     * @param string $absolutePath
     * @param mixed  $data
     * @param bool   $json_encode
     *
     * @return bool
     */
    public function write(string $absolutePath, $data, bool $json_encode = false): bool
    {
        return (bool)$this->writer->write($absolutePath, $data, $json_encode);
    }

    /**
     * Read data from file.
     *
     * @param string $absolutePath
     * @param bool   $json_decode
     * @param bool   $resulting    If the file is a PHP script this parameter will return the output instead of the content.
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
            $this->fs->appendToFile($absolutePath, null);

            $this->createIfAbsent($absolutePath);
        }

        return true;
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
        // return $this->getMetaKey('root_dir', $this->cwd) . $this->formatPath($path) ?: '';
        return '/database/quark/' . $this->formatPath($path) ?: '';
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
        return $this->rootPath('/database/quark/' . ($this->formatPath($path) ?: ''));
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
        return $this->quarkPath() . 'tables' . ($this->formatPath($path) ?: '');
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
        return $this->quarkPath() . 'migrations' . ($this->formatPath($path) ?: '');
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
        return $this->quarkPath() . '_meta' . ($this->formatPath($path) ?: '');
    }

    /**
     * Set a key in the internal config file.
     *
     * @param string $key
     * @param $value
     * @param bool   $overwriteExisting
     *
     * @return bool
     */
    public function setMetaKey(string $key, $value, bool $overwriteExisting = true): bool
    {
        $metaFile = $this->metaPath('internal_config.qrk');

        if ($this->fs->exists($metaFile)) {
            $internalConfig = $this->read($metaFile, true);

            if ($overwriteExisting) {
                $internalConfig[$key] = $value;
            } elseif (!array_key_exists($key, $internalConfig)) {
                $internalConfig[$key] = $value;
            }

            $this->write($metaFile, $internalConfig, true);
        } else {
            $this->fs->appendToFile($metaFile, json_encode([$key => $value]));
        }

        return false;
    }

    /**
     * Get a key from internal config.
     *
     * @param string $key
     * @param $default
     *
     * @return false|mixed|string
     */
    public function getMetaKey(string $key, $default = null)
    {
        $metaFile = $this->metaPath('internal_config.qrk');

        if ($this->fs->exists($metaFile)) {
            $internalConfig = $this->read($metaFile, true);

            return array_key_exists($key, $internalConfig) ? $internalConfig[$key] : $default;
        }

        return $default;
    }

    /**
     * Determine if path needs an additional separator.
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
