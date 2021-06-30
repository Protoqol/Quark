<?php

namespace Protoqol\Quark\Database;

class QCollection
{
    /**
     * @var $items
     */
    protected $items;

    /**
     * QCollection constructor.
     *
     * @param array  $data
     * @param string $classCast
     */
    public function __construct(array $data, string $classCast)
    {
        $this->items = array_map(
            static function ($row) use ($classCast) {
                return new $classCast($row, true);
            }, $data
        );
    }

    /**
     * Get first item in collection.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->items[0];
    }

    /**
     * Get last item in collection.
     *
     * @return mixed
     */
    public function last()
    {
        return $this->items[count($this->items) - 1];
    }

    public function getValue(string $key)
    {
        // @TODO implement getValue functionality.
    }

    /**
     * Cast items to json encoded string.
     *
     * @return false|string
     */
    public function __toString(): string
    {
        return json_encode($this->__toArray());
    }

    /**
     * Cast items to array.
     *
     * @return array
     */
    private function __toArray(): array
    {
        return array_map(
            static function ($item) {
                return (array)$item->attributes;
            }, $this->items
        );
    }
}
