<?php


namespace Protoqol\Quark\IO;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class Writer
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * Writer constructor.
     */
    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static)->{$name}($arguments);
    }

    /**
     * @param string $absolutePath
     * @param $data
     * @param bool   $json_encode
     *
     * @return false|int
     */
    public function write(string $absolutePath, $data, bool $json_encode = false)
    {
        if ($this->fs->exists($absolutePath)) {
            $data = $json_encode ? json_encode($data) : $data;

            return file_put_contents($absolutePath, $data);
        }

        throw new FileNotFoundException('File at "' . $absolutePath . '" does not exist.');
    }
}
