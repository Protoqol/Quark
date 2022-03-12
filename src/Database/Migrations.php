<?php

namespace Protoqol\Quark\Database;

use Carbon\Carbon;
use DirectoryIterator;
use Exception;
use Generator;

/**
 * Class Migrations
 *
 * @package Protoqol\Quark\Database
 */
class Migrations
{
    /**
     * @var int
     */
    public $pendingMigrations;

    /**
     * @var string
     */
    public $latestMigration;

    /**
     * @var bool $dryRun
     */
    private $dryRun;

    /**
     * @var string
     */
    private $migrationsFile;

    /**
     * @var DirectoryIterator
     */
    private $migrationsIterator;

    /**
     * Migrations constructor.
     *
     * @param bool $dryRun
     */
    public function __construct(bool $dryRun = false)
    {
        $this->dryRun = $dryRun;

        if (!$this->dryRun) {
            quark()->createIfAbsent(quark()->metaPath('_migrations.qrk'));
        }

        $this->migrationsFile = quark()->metaPath('_migrations.qrk');

        $this->migrationsIterator = new DirectoryIterator(quark()->migrationsPath());

        $this->pendingMigrations = $this->getPending();

        $this->latestMigration = $this->getLatestMigratedFile();

    }

    /**
     * Get pending migrations.
     *
     * @return int
     */
    private function getPending(): int
    {
        $excluded = $this->getExcluded();
        $pending = 0;

        foreach ($this->migrationsIterator as $file) {
            if (str_contains($file->getFilename(), '.php') && !in_array($file->getFilename(), $excluded, true)) {
                $pending++;
            }
        }

        return $pending;
    }

    /**
     * Get migrations that have already been executed.
     *
     * @return array
     */
    private function getExcluded(): array
    {
        $excluded = null;

        if (!quark()->read($this->migrationsFile, true)) {
            return [];
        }

        foreach (quark()->read($this->migrationsFile, true) as $migration) {
            $excluded[] = $migration['name'];
        }

        return $excluded ?: [];
    }

    /**
     * Get last entry in meta/__migrations.qrk.
     *
     * @return array
     */
    private function getLatestMigratedFile(): array
    {
        if (!quark()->read($this->migrationsFile, true)) {
            return [];
        }

        $migrated = quark()->read($this->migrationsFile, true);

        return $migrated[(count($migrated) - 1)];
    }

    /**
     * Generate migration for $tableName.
     *
     * @param string $tableName
     *
     * @return bool
     * @throws Exception
     */
    public function generate(string $tableName): bool
    {
        if (!quark()->fs->exists(quark()->migrationsPath())) {
            throw new Exception('Migrations directory could not be found.');
        }

        $timestamp = substr((string)Carbon::now()->unix(), -6);

        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tableName));

        $realPath = quark()->migrationsPath($timestamp . '_' . $tableName . '.php');

        try {
            quark()->fs->touch($realPath);

            if ($this->insertStub($realPath, $tableName)) {
                return true;
            }

            throw new Exception('Could not fill content for migration.');

        } catch (Exception $exception) {
            throw new Exception('Could not generate migration.');
        }
    }

    /**
     * Populate migration with migration stub.
     *
     * @param string $migrationFilePath
     * @param string $tableName
     *
     * @return bool
     */
    private function insertStub(string $migrationFilePath, string $tableName): bool
    {
        // @TODO refactor.
        $assumedQuarkPackageLocation = '/vendor/protoqol/quark/stubs/migration.stub';

        $stubPath = quark()->rootPath($assumedQuarkPackageLocation);

        $migrationStubString = quark()->read($stubPath);

        $content = str_replace('{name}', $tableName, $migrationStubString);

        return quark()->write($migrationFilePath, $content);
    }

    /**
     * Reset migrations file.
     *
     * @return bool
     */
    public function reset(): bool
    {
        $migrationsFile = quark()->metaPath() . DIRECTORY_SEPARATOR . '_migrations.qrk';

        if (!quark()->fs->exists($migrationsFile)) {
            return true;
        }

        quark()->fs->remove($migrationsFile);
    }

    /**
     * Run migrations.
     *
     * @throws Exception
     */
    public function run(): Generator
    {
        $excludedMigrations = $this->getExcluded();

        foreach ($this->migrationsIterator as $file) {
            if (str_contains($file->getFilename(), '.php') && !in_array($file->getFilename(), $excludedMigrations, true)) {
                $migrationContent = quark()->read($file->getPathname(), false, true);
                yield $this->process($migrationContent, $file->getFilename());
            }
        }
    }

    /**
     * Process migration.
     *
     * @param array  $migration
     * @param string $filename
     *
     * @return string
     * @throws Exception
     */
    private function process(array $migration, string $filename): string
    {
        foreach ($migration as $table => $columns) {
            if (!count($columns)) {
                throw new Exception('No columns defined for ' . $table . '. ');
            }

            $target = quark()->tablesPath($table . '.qrk');

            if (!$this->dryRun) {
                quark()->fs->touch($target);
            }

            $columnsDefinition = [];

            foreach ($columns as $column => $type) {
                $columnsDefinition[] = [$column => $type];
            }

            $columnsDefinition = json_encode($columnsDefinition, JSON_UNESCAPED_UNICODE);

            $structure = "{\"__meta\":{\"__table\":\"{$table}\",\"__columns\":{$columnsDefinition}},\"__\":{}}";

            if (!$this->dryRun) {
                quark()->write($target, $structure);
            }

            if (!$this->dryRun) {
                if (!$this->record($filename)) {
                    throw new Exception("Could not register migration for " . $table);
                }
            }
        }

        return $filename;
    }

    /**
     * Keep track of migration in meta/__migrations.qrk.
     *
     * @param string $filename
     *
     * @return bool
     */
    private function record(string $filename): bool
    {
        $migrations = quark()->read($this->migrationsFile, true);

        if (!$migrations) {
            // Write empty json array to migrations file.
            quark()->write($this->migrationsFile, [], true);

            $migrations = quark()->read($this->migrationsFile, true);
        }

        $migrations[] = [
            "name"        => $filename,
            "migrated_at" => Carbon::now()->unix()
        ];

        quark()->write($this->migrationsFile, $migrations, true);

        return true;
    }
}
