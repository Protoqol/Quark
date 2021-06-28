<?php


namespace Protoqol\Quark\IO;


/**
 * Class Database
 *
 * @package Protoqol\Quark\IO
 */
class Database
{
    /**
     * @var string
     */
    private $table;
    /**
     * @var array
     */
    private $columns;

    /**
     * Database constructor.
     *
     * @param string $table
     * @param array  $columns
     */
    public function __construct(string $table, array $columns)
    {
        $this->table = $table;
        $this->columns = $columns;
    }

    public function generateDatabase()
    {
        dd($GLOBALS['ROOT_DIR'] . env('QUARK_DIRECTORY', '/database/quark/tables/'));
    }
}
