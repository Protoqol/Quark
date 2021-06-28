<?php


namespace Protoqol\Quark\IO;


use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Writer
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
     * @param        $data
     * @param bool   $json_encoded
     *
     * @return false|int
     */
    public function write(string $absolutePath, $data, bool $json_encoded = false)
    {
        if (quark()->fs->exists($absolutePath)) {

            $data = $json_encoded ? json_encode($data) : $data;

            return file_put_contents($absolutePath, $data);
        }

        throw new FileNotFoundException('File at "' . $absolutePath . '" does not exist.');
    }
}
