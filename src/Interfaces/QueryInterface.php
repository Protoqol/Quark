<?php

namespace Protoqol\Quark\Interfaces;

/**
 * Interface QueryInterface
 *
 * @package Protoqol\Interfaces\Quark
 */
interface QueryInterface
{

    /**
     * Select `database`.`table` to execute query upon
     *
     * @param string      $table
     * @param string|null $database
     *
     * @return mixed
     */
    public function from(string $table, string $database = NULL);

    /**
     * Set parameters for data to be retrieved
     *
     * @param string $column
     * @param        $value
     *
     * @return mixed
     */
    public function whereIs(string $column, $value);

    /**
     * Set parameters for data to be retrieved
     *
     * @param string $column
     * @param        $value
     *
     * @return mixed
     */
    public function whereIsNot(string $column, $value);

    /**
     * Get result of query
     *
     * @return mixed
     */
    public function get();

    /**
     * Get first value in result set
     *
     * @param array $colums
     *
     * @return mixed
     */
    public function first(array $colums = ['*']);
}
