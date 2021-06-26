<?php

namespace Protoqol\Quark\IO;

use Exception;
use Protoqol\Quark\Connection\DatabaseAccessor;
use Protoqol\Quark\Exceptions\ColumnNotFoundException;
use Protoqol\Quark\Quark;

/**
 * Class QuarkPrefetch
 *
 * @package Protoqol\Quark
 */
class QuarkPrefetch
{

    /**
     * @var DatabaseAccessor
     */
    private $connection;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $database;

    /**
     * QuarkPrefetch constructor.
     *
     * @param string $database
     * @param string $table
     */
    public function __construct(string $database, string $table)
    {
        $this->connection = (new DatabaseAccessor());
        $this->data       = $this->connection->getData();
        $this->database   = $database;
        $this->table      = $table;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function databaseExists(): bool
    {
        if (property_exists($this->data, $this->database)) {
            return true;
        } else {
            throw new Exception("Quark Says: Database '{$this->database}' does not exist.");
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function tableExists(): bool
    {
        if ($this->databaseExists()) {
            $tablesInDatabase = $this->data->{$this->database}->{Quark::META_ACCESSOR}->tables;

            foreach ($tablesInDatabase as $table) {

                if ($table === $this->table) {
                    return true;
                }
            }

            throw new Exception("Quark Says: Table '{$this->table}' does not exist in database '{$this->database}'.");
        }

        return false;
    }

    /**
     * @param array $columns
     *
     * @return bool
     * @throws Exception
     */
    public function columnsExists(array $columns = ['*']): bool
    {
        if ($columns === ['*']) {
            return true;
        }

        if ($this->tableExists()) {
            $metaColumns = $this->data->{$this->database}->{$this->table}[0]->keys;

            foreach ($metaColumns as $column) {

                foreach ($columns as $specColumn) {

                    if ($column->column === $specColumn) {
                        return true;
                    }
                }
            }

            $str = implode(', ', $columns);

            throw new ColumnNotFoundException("Quark Says: Column(s) '{$str}' not found in '{$this->database}'.'{$this->table}'");
        }

        return false;

    }

    /**
     * @param array $columns
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

}
