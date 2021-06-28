<?php

namespace Protoqol\Quark\Database;

use Protoqol\Quark\Helpers\Str;
use Protoqol\Quark\IO\Reader;
use Protoqol\Quark\Traits\QCollector;

/**
 * Class QModel
 *
 * @package Protoqol\Quark\Database
 */
abstract class QModel
{
    use QCollector;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var
     */
    public $table;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * QModel constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;

        if (!$this->table) {
            $this->table = $this->resolveTableName();
        }

        $this->reader = new Reader();
    }

    /**
     * Get collection of records from model.
     *
     * @param array $columns
     *
     * @return QCollection
     */
    public function get(array $columns = ['*']): QCollection
    {
        $data = $this->reader->readData($this->table);

        return $this->toQCollection($data['__'], $data['__meta'], $columns);
    }

    /**
     * Get first record from model.
     *
     * @param array|string[] $columns
     *
     * @return $this
     */
    public function first(array $columns = ['*']): QModel
    {
        $data = $this->reader->readData($this->table);

        $collection = $this->toQCollection($data['__'], $data['__meta'], $columns);

        $this->attributes = $collection->first();

        return new static($collection->first());
    }

    /**
     * Query this model.
     *
     * @return $this
     */
    public function where(string $key, string $operator, string $value): QModel
    {
        return $this;
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    /**
     * Resolve table name.
     *
     * @return string
     */
    private function resolveTableName(): string
    {
        $particles = explode('\\', static::class);

        return Str::pluralize(strtolower($particles[count($particles) - 1]));
    }

    /**
     * Handle static calls to QModel class.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }

    /**
     * Get model property.
     *
     * @param string $property
     *
     * @return mixed
     */
    private function getProperty(string $property)
    {
        return $this->attributes[$property] ?: null;
    }

    /**
     * Set model property. Does not persist if not implicitly stated.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return void
     */
    private function setProperty(string $property, $value): void
    {
        $this->attributes[$property] = $value;
    }

    /**
     * Get model property.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->getProperty($property);
    }

    /**
     * Set model property.
     *
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->setProperty($property, $value);
    }

    /**
     * Check if property exists on model.
     *
     * @param $property
     *
     * @return bool
     */
    public function __isset($property)
    {
        return (in_array($property, $this->attributes, true));
    }

    /**
     * Convert QModel to string version.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'toString';
    }
}
