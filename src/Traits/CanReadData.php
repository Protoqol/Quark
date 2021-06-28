<?php


namespace Protoqol\Quark\Traits;


use Protoqol\Quark\IO\Reader;

trait CanReadData
{
    /**
     * @var $raw_data
     */
    protected $raw_data;

    /**
     * @var $meta_data
     */
    protected $meta_data;

    /**
     * @var $data
     */
    protected $data;

    /**
     * @var
     */
    protected $columns;

    /**
     * Read table data.
     *
     * @return array
     */
    public function read(): array
    {
        $data = (new Reader())->readData($this->resolveTableName());
        $this->raw_data = $data;
        $this->meta_data = $data['__meta'];
        $this->columns = $this->flattenColumns($data['__meta']['__columns']);
        $this->data = $data['__'];

        return $data;
    }
}
