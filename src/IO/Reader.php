<?php


namespace Protoqol\Quark\IO;


use JsonMachine\JsonMachine;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;

class Reader
{
    /**
     * @var Filesystem
     */
    private $fs;

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
     * Get file output (read or execute).
     *
     * @param string $absolutePath
     * @param bool   $resulting If the file is a PHP script this parameter will return the output instead of the content.
     * @param bool   $json_decode
     *
     * @return string|array
     */
    public function read(string $absolutePath, bool $resulting = false, bool $json_decode = false)
    {
        if ($this->fs->exists($absolutePath)) {

            if (!$resulting) {
                $output = file_get_contents($absolutePath);
            } else {
                $output = require $absolutePath;
            }

            if ($json_decode === true) {
                $output = json_decode($output, true);
            }

            return $output;
        }

        throw new FileNotFoundException('File at "' . $absolutePath . '" does not exist.');
    }

    /**
     * Read table data.
     *
     * @param string $table
     *
     * @return array
     */
    public function readData(string $table)
    {
        // @TODO refactor, this is a temporary workaround.
        $tmp = getcwd() . "/../database/quark/tables/" . $table . '.qrk';

        if ($this->fs->exists($tmp)) {
            $meta = [];

            $metaIterator = JsonMachine::fromFile($tmp);

            foreach ($metaIterator as $key => $value) {
                $meta[$key] = $value;
            }

            return $meta;
        }

        throw new FileNotFoundException('File at "' . $tmp . '" does not exist.');
    }
}
