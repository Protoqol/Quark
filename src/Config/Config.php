<?php

namespace Protoqol\Quark\Config;

use Dotenv\Dotenv;

class Config
{
    /**
     * @var Dotenv
     */
    private Dotenv $config;

    /**
     * @var string
     */
    private string $file_name = 'quark-env';

    /**
     * Config constructor.
     */
    public function __construct(string $path)
    {
        $this->config = Dotenv::createImmutable($path, $this->file_name);
        $this->config->load();
    }
}
