<?php


namespace Protoqol\Quark\Connection;

/**
 * Class DatabaseAccessor
 *
 * @package Protoqol\Quark\Connection
 */
class DatabaseAccessor
{

    /**
     * Contains file database file location
     *
     * @var string
     */
    public string $file;

    /**
     * @var string $backup
     */
    public string $backup;

    /**
     * @var $data
     */
    public $data;

    /**
     * DatabaseAccessor constructor.
     *
     * @param string|null $file
     */
    public function __construct(string $file = NULL)
    {
        $this->file = $this->fileResolver();

        if ($file !== NULL) {
            $this->file = $file;
        }
    }

    /**
     * Get connection path
     *
     * @return object
     */
    public function getInstance(): object
    {
        return (object)[
            'isConnected' => is_dir($this->fileResolver()),
            'path'        => $this->fileResolver(),
        ];
    }

    /**
     * Backup database in case of error
     *
     * @return bool
     */
    public function prepareForEdit(): bool
    {
        $this->backup = file_get_contents($this->file);

        return $this->backup;
    }

    /**
     * @param bool   $json_decoded
     *
     * @param string $database
     *
     * @return mixed
     * @throws \JsonException
     */
    public function getData(bool $json_decoded = true, string $database = '')
    {
        $this->prepareForEdit();
        $this->data = $this->backup;

        if (!empty($database)) {
            return $json_decoded ? json_decode($this->data, true, 512, JSON_THROW_ON_ERROR)->{$database} : $this->data;
        }

        return $json_decoded ? json_decode($this->data, true, 512, JSON_THROW_ON_ERROR) : $this->data;
    }

    /**
     * Find quark database location
     *
     * @return string
     */
    private function fileResolver(): string
    {
        $presumedFileName = '/database/quark';
        $fileLocation = '';

        if (file_exists(dirname(__DIR__) . $presumedFileName)) {
            $fileLocation = dirname(__DIR__) . $presumedFileName;
        }

        if (file_exists($GLOBALS['ROOT_DIR'] . '/..' . $presumedFileName)) {
            $fileLocation = $GLOBALS['ROOT_DIR'] . '/..' . $presumedFileName;
        }

        return $fileLocation;
    }
}
