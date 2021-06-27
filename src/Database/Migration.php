<?php

namespace Protoqol\Quark\Database;

use Carbon\Carbon;
use DirectoryIterator;
use Exception;
use Protoqol\Quark\IO\Database;
use Symfony\Component\Filesystem\Filesystem;

class Migration
{
    /**
     * @var Filesystem
     */
    private Filesystem $fs;

    /**
     * @var string
     */
    private string $migrations_directory;

    /**
     * @var string
     */
    private string $timestamp_format = 'Y_m_dhs';

    /**
     * Migration constructor.
     */
    public function __construct()
    {
        $this->fs = new Filesystem();
        $this->migrations_directory = $GLOBALS['ROOT_DIR'] . DIRECTORY_SEPARATOR . env('QUARK_DIRECTORY', 'database/quark/migrations');
    }

    /**
     * Create or confirm (the existence) the migrations directory.
     *
     * @return bool
     */
    private function createOrConfirmMigrationDirectory(): bool
    {
        if ($this->fs->exists($this->migrations_directory)) {
            return true;
        }

        try {
            $this->fs->mkdir($this->migrations_directory);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Generate a migration file.
     *
     * @param string $tableName
     *
     * @return bool
     * @throws Exception
     */
    public function generateMigrationFile(string $tableName): bool
    {
        if (!$this->createOrConfirmMigrationDirectory()) {
            throw new Exception('Migrations directory could not be found.');
        }

        $dir = env('QUARK_DIRECTORY', '/database/quark/migrations');

        $timestamp = substr((string)Carbon::now()->unix(), -6);

        $path = $GLOBALS['ROOT_DIR'] . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;

        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tableName));

        $fileName = $timestamp . '_' . $tableName . '.php';

        $realPath = $path . $fileName;

        try {
            $this->fs->touch($realPath);

            if ($this->insertMigrationStub($realPath, $tableName)) {
                return true;
            }

            throw new Exception('Could not fill content for migration.');

        } catch (Exception $exception) {
            throw new Exception('Could not generate migration.');
        }
    }

    /**
     * Fill migration file with pre-made migration stub.
     *
     * @param string $migrationFilePath
     * @param string $tableName
     *
     * @return bool
     */
    private function insertMigrationStub(string $migrationFilePath, string $tableName): bool
    {
        $stubPath = $GLOBALS['ROOT_DIR'] . '/vendor/protoqol/quark/stubs/migration.stub';

        $migrationStubString = file_get_contents($stubPath);

        $content = str_replace('{name}', $tableName, $migrationStubString);

        return (bool)file_put_contents($migrationFilePath, $content);
    }

    /**
     * Run migrations.
     */
    public function runMigrationFiles(): void
    {
        $dir = $GLOBALS['ROOT_DIR'] . env('QUARK_DIRECTORY', '/database/quark/migrations');

        foreach (new DirectoryIterator($dir) as $file) {
            if (!$file->isDot()) {
                $migration = require($file->getPathname());

                (new Database($migration));
            }
        }
    }
}
