<?php

namespace Protoqol\Quark;

class Config
{

    public function __construct()
    {
        //
    }

    public function testFunc()
    {
        $query = (new Query())->testFunc();

        return 'Config works ' . $query;
    }

}
