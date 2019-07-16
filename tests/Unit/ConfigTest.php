<?php

use PHPUnit\Framework\TestCase;
use Protoqol\Quark\Config;

class ConfigTest extends TestCase
{

    public function testConfigReturnsString()
    {
        $config = new Config();
        $this->assertIsString($config->testFunc());
    }

}
