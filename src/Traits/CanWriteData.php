<?php


namespace Protoqol\Quark\Traits;

use Protoqol\Quark\IO\Writer;

trait CanWriteData
{
    /**
     * Add row to table.
     *
     * @return mixed
     */
    public function persistAttributes(array $attributes)
    {
        $writer = new Writer();

        $mirror = $this->raw_data;

        $this->data[] = $attributes;

        $mirror['__'] = $this->data;

        $path = getcwd() . "/../database/quark/tables/" . $this->resolveTableName() . '.qrk';

        if ($writer->write($path, $mirror, true)) {
            return $this->attributes;
        }

        return false;
    }

    /**
     * Remove row from table.
     */
    public function deleteRow()
    {
        // @TODO
    }
}
