<?php

namespace Protoqol\Quark;

class Table
{

    public function __construct()
    {
        //
    }

    /**
     * @param string $tableName
     * @param array $columns
     *
     * @return string
     */
    public function generateTable(string $tableName, ?array $columns = []): string
    {
        if (empty($columns) || !isset($columns) || !count($columns) > 0) {
            return '{"' . $tableName . '": {}}';
        }

        return false;
    }

}
