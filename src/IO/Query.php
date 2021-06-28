<?php

namespace Protoqol\Quark\IO;

use Exception;
use Protoqol\Quark\Connection\DatabaseAccessor;
use Protoqol\Quark\Interfaces\QueryInterface;
use Protoqol\Quark\Quark;

/**
 * Class Query
 *
 * @package Protoqol\Quark
 */
class Query implements QueryInterface
{

    /**
     * @var DatabaseAccessor
     */
    public $connection;

    /**
     * Hold database data
     *
     * @var $data
     */
    public $data;

    /**
     * Hold database backup
     *
     * @var $backup
     */
    public $backup;

    /**
     * @var mixed $database
     */
    public $database;

    /**
     * @var string $table
     */
    public $table;

    /**
     * @var QuarkPrefetch
     */
    private $prefetch;

    /**
     * @var int
     */
    private $limit;

    /**
     * Query constructor. Establish connection with database file.
     *
     * @param string $database
     * @param string $table
     *
     * @throws Exception
     */
    public function __construct(string $database = '', string $table = '')
    {
        $this->connection = (new Quark())->connection();
        $this->database = $database !== '' ? $database : env('DB_DATABASE');
        $this->table = $table;

        if (!$this->connection->getInstance()->isConnected) {
            throw new Exception('Could not find quark database. Are you sure it exists? (root/database/quark/database.qrk)');
        }

        $this->prefetch = new QuarkPrefetch($this->database, $this->table);
        $this->backup = $this->connection->getData();
        $this->data = $this->backup->{$this->database};
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return Query
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name === 'from') {
            dd($arguments);

            return (new Query());
        }
    }

    /**
     * Select `database`.`table` to execute query upon
     *
     * @param string      $table
     * @param string|null $database
     *
     * @return mixed
     * @throws Exception
     */
    public static function table(string $table, string $database = NULL)
    {
        return (new Query())->from($table, $database);
    }

    /**
     * Select `database`.`table` to execute query upon
     *
     * @param string      $table
     * @param string|null $database
     *
     * @return mixed
     */
    public function from(string $table, string $database = NULL)
    {
        $this->table = $table;
        $this->database = $database ?? $this->database;

        $this->prefetch = new QuarkPrefetch($this->database, $this->table);
        $this->data = $this->backup->{$this->database}->{$this->table};

        return $this;
    }

    /**
     * @param $column
     * @param $value
     *
     * @return Query
     */
    public function whereIs($column, $value): Query
    {
        $__data = $this->data;
        $__meta = (array)array_shift($__data);

        foreach ($__data as $key => $val) {
            if ($__data[$key]->{$column} != $value) {
                unset($__data[$key]);
            }
        }

        $this->data = $__data;
        array_unshift($this->data, $__meta);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     *
     * @return Query
     */
    public function whereIsNot($column, $value)
    {
        $__data = $this->data;
        $__meta = (array)array_shift($__data);

        foreach ($__data as $key => $val) {
            if ($__data[$key]->{$column} == $value) {
                unset($__data[$key]);
            }
        }

        $this->data = $__data;
        array_unshift($this->data, $__meta);

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return Query
     */
    public function limit(int $limit = 50)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return mixed
     */
    public function first(array $columns = ['*'])
    {
        $__data = $this->data;
        $__meta = array_shift($__data);

        if ($columns === ['*']) {
            return $__data[0];
        }

        foreach ($__data[0] as $key => $val) {
            foreach ($columns as $column) {
                if ($column !== $key) {
                    unset($__data[0]->{$key});
                }
            }
        }

        return $__data[0];
    }

    /**
     * @param array|null $columns
     *
     * @return array
     * @throws Exception
     */
    public function get(array $columns = ['*'])
    {
        $this->prefetch->columnsExists($columns);

        if (!$this->connection->prepareForEdit()) {
            throw new Exception('Could not make a content copy of database.qrk, aborting.');
        }

        if ($columns !== ['*'] && $columns === []) {
            throw new Exception('Could not retrieve data, key is missing in function call @ get(string $key).');
        }

        $__data = $this->data;
        array_shift($__data);

        if ($columns === ['*']) {

            if (isset($this->limit)) {
                $__data = array_splice($__data, 0, $this->limit);
            }

            return collect($__data);
        }

        foreach ($__data as &$entry) {
            foreach ($columns as $column) {
                foreach ($entry as $key => $value) {
                    if ($key !== $column) {
                        unset($entry->{$key});
                    }
                }
            }
        }

        if (isset($this->limit)) {
            $__data = array_splice($__data, 0, $this->limit);
        }

        return collect($__data);
    }

}
