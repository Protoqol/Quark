<?php

namespace Protoqol\Quark\Database;

use Carbon\Carbon;
use Protoqol\Quark\Traits\CanReadData;
use Protoqol\Quark\Traits\CanWriteData;
use Protoqol\Quark\Traits\ModelResolver;
use Protoqol\Quark\Traits\QCollector;

/**
 * Class QModel
 *
 * @package Protoqol\Quark\Database
 */
abstract class QModel
{
    use QCollector, ModelResolver, CanReadData, CanWriteData;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @var
     */
    public $table;

    /**
     * @var array
     */
    private $backup;

    /**
     * QModel constructor.
     *
     * @param array $attributes
     * @param bool  $fresh This parameter controls whether or not the data should be retrieved or not.
     */
    public function __construct(array $attributes = [], bool $fresh = false)
    {
        $this->attributes = $attributes;
        $this->backup = $attributes;

        if (!$this->table) {
            $this->table = $this->resolveTableName();
        }

        if (!$fresh) {
            $this->read();
        }
    }

    /**
     * Flatten columns.
     *
     * @param array $columns
     *
     * @return array
     */
    private function flattenColumns(array $columns): array
    {
        $metaColumns = [];
        foreach ($columns as $key) {
            foreach ($key as $columnName => $columnType) {
                $metaColumns[] = [
                    'name' => $columnName,
                    'type' => $columnType
                ];
            }
        }
        return $metaColumns;
    }

    /**
     * Get all records from model.
     *
     * @param array $columns
     *
     * @return QCollection
     */
    public static function all(array $columns = ['*']): QCollection
    {
        $model = new static;

        return self::toQCollection($model->data, $model->meta_data, $columns);
    }

    /**
     * Get collection of records from model.
     *
     * @param array $columns
     *
     * @return QCollection
     */
    public static function get(array $columns = ['*']): QCollection
    {
        $model = new static;

        return self::toQCollection($model->data, $model->meta_data, $columns);
    }

    /**
     * Get first record from model.
     *
     * @param array|string[] $columns
     *
     * @return $this
     */
    public static function first(array $columns = ['*']): QModel
    {
        $model = new static;

        $collection = self::toQCollection($model->data[0], $model->meta_data, $columns);

        $model->attributes = $collection->first();

        return $collection->first();
    }

    /**
     * Get last record from model.
     *
     * @param array|string[] $columns
     *
     * @return $this
     */
    public static function last(array $columns = ['*']): QModel
    {
        $model = new static;

        $collection = self::toQCollection($model->data[count($model->data) - 1], $model->meta_data, $columns);

        $model->attributes = $collection->last();

        return $collection->last();
    }

    /**
     * Query this model.
     *
     * @return $this
     */
    public static function where(string $key, string $operator, string $value): QModel
    {
        return new static;
    }

    /**
     * Create new record.
     *
     * @param array $attributes
     *
     * @return QModel
     * @throws \Exception
     */
    public static function create(array $attributes): QModel
    {
        $model = new static($attributes);

        return $model->persist();
    }

    public static function update()
    {
        // @TODO implement update functionality.
    }

    public static function delete()
    {
        // @TODO implement delete functionality.
    }

    /**
     * Persist data to database.
     *
     * @return QModel
     * @throws \Exception
     */
    private function persist(): QModel
    {
        $indexedCompleteAttributes = [];
        $completeAttributes = [];

        $i = 0;
        array_walk_recursive($this->columns, function ($key) use (&$indexedCompleteAttributes, &$completeAttributes, &$i) {
            if ($i % 2 === 0) {
                try {
                    // Attribute has been given.
                    $indexedCompleteAttributes[] = $this->attributes[$key];
                    $completeAttributes[$key] = $this->attributes[$key];
                } catch (\Exception $e) {
                    // Attribute needs to be generated.

                    // @TODO refactor.
                    if ($key === 'id') {
                        $lastId = $this->last(['id'])->id;
                        $indexedCompleteAttributes[] = $lastId + 1;
                        $completeAttributes[$key] = $lastId + 1;
                    } elseif ($key === 'created_at' || $key === 'updated_at') {
                        $indexedCompleteAttributes[] = Carbon::now()->unix();
                        $completeAttributes[$key] = Carbon::now()->unix();
                    } else {
                        $indexedCompleteAttributes[] = null;
                        $completeAttributes[$key] = null;
                    }
                }
            }
            $i++;
        });

        $this->attributes = $completeAttributes;

        if ($this->persistAttributes($indexedCompleteAttributes)) {
            return $this;
        }

        throw new \Exception('Could not create new record.');
    }

    /**
     * Handle dynamic calls to QModel class.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return (new static)->$method(...$arguments);
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
        return $this->attributes[$property] ?? null;
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
        return json_encode($this->attributes);
    }
}
