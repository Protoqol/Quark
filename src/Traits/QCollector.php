<?php

namespace Protoqol\Quark\Traits;

use Carbon\Carbon;
use Protoqol\Quark\Database\QCollection;

trait QCollector
{
    /**
     * Convert data to QCollection.
     *
     * @param array $data
     * @param array $meta
     * @param array $columns
     *
     * @return QCollection
     */
    protected static function toQCollection(array $data, array $meta, array $columns = ['*']): QCollection
    {
        # Get flat list of column names to assign them to retrieved data.
        $metaColumns = [];
        foreach ($meta['__columns'] as $key) {
            foreach ($key as $columnName => $columnType) {
                $metaColumns[] = [
                    'name' => $columnName,
                    'type' => $columnType,
                ];
            }
        }

        if (isset($data[0]) && is_array($data[0])) {
            $i = 0;
            foreach ($data as $rows) {
                foreach ($rows as $key => $value) {
                    $column = $metaColumns[$key];

                    if (is_string($columns[0])) {
                        if ($columns[0] === '*') {
                            $data[$i][$column['name']] = self::getTypedValue($value, $column['type']);
                        }
                    } # Check what columns are requested. Asterisk means all columns will be returned.
                    elseif (in_array(array_values($column)[0], $columns[0], true) || $columns[0][0] === '*') {
                        $data[$i][$column['name']] = self::getTypedValue($value, $column['type']);
                    }

                    unset($data[$i][$key]);
                }

                $i++;
            }
        } else {
            foreach ($data as $key => $value) {
                $column = $metaColumns[$key];

                # Check what columns are requested. Asterisk means all columns will be returned.
                if (in_array(array_values($column)[0], $columns, true) || $columns[0] === '*') {
                    $data[$column['name']] = self::getTypedValue($value, $column['type']);
                }

                unset($data[$key]);
            }
            $data = [$data];
        }

        return new QCollection($data, static::class);
    }

    /**
     * Transform value to type as defined in column definition.
     *
     * @param        $value
     * @param string $type
     *
     * @return int|mixed|string
     */
    private static function getTypedValue($value, string $type)
    {
        switch ($type) {
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                return (bool)$value;
            case 'int':
            case 'integer':
                return (int)$value;
            case 'timestamp':
            case 'utimestamp':
                return Carbon::parse($value)->toDateTimeString();
            default:
                return $value;
        }
    }
}
