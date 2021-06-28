<?php


namespace Protoqol\Quark\IO;


use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Reader
{
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
     * @param bool   $resulting If the file is a PHP script this parameter will return the output instead of the content.
     * @param bool   $json_decode
     *
     * @return string|array
     */
    public function read(string $absolutePath, bool $resulting = false, bool $json_decode = false)
    {
        if (quark()->fs->exists($absolutePath)) {

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
}
