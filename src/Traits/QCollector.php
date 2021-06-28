<?php

namespace Protoqol\Quark\Traits;

use Protoqol\Quark\Database\QCollection;

trait QCollector
{
    protected function toQCollection(array $data, array $meta, array $columns = []): QCollection
    {
        return new QCollection($data, $meta, $columns);
    }
}
