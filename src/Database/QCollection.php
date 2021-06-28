<?php

namespace Protoqol\Quark\Database;

use Carbon\Carbon;

class QCollection
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var $items
     */
    protected $items;

    /**
     * QCollection constructor.
     *
     * @param array $data
     * @param array $meta
     * @param       $columns
     */
    public function __construct(array $data, array $meta, $columns)
    {
        $this->data = $data;
        $this->meta = $meta;

        // Get flat list of column names to assign them to retrieved data.
        $mirror = [];
        array_walk($this->meta['__columns'], function (&$key) use (&$mirror) {
            foreach ($key as $columnName => $columnType) {
                $mirror[] = [
                    'name' => $columnName,
                    'type' => $columnType
                ];
            }
        });

        $this->columns = $mirror;

        $this->items = $this->collect();
    }

    /**
     * Collect data.
     *
     * @param array $columns
     *
     * @return array
     */
    public function collect(array $columns = []): array
    {
        $i = 0;
        $mirror = $this->data;

        foreach ($this->data as $rows) {
            foreach ($rows as $key => $value) {
                $column = $this->columns[$key];
                $mirror[$i][$column['name']] = $this->getTypedValue($value, $column['type']);
                unset($mirror[$i][$key]);
            }

            $i++;
        }

        return $mirror;
    }

    /**
     * Get first item from collection.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->items[0];
    }

    /**
     * Transform value to type as defined in column definition.
     *
     * @param        $value
     * @param string $type
     *
     * @return int|mixed|string
     */
    private function getTypedValue($value, string $type)
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
