<?php


use Symfony\Component\Filesystem\Filesystem;

class Quark
{

    /**
     * FileSystem instance
     * @var Symfony\Component\Filesystem\Filesystem $fs
     */
    private $fs;

    /**
     * Current working directory
     * @var string $cwd
     */
    private $cwd;

    public function __construct(string $cwd)
    {
        $this->fs  = new Filesystem();
        $this->cwd = $cwd;
    }

    /**
     * Set an Quark executable in current directory.
     * @return bool
     */
    public function setExecutable(): bool
    {
        $dev_origin = $this->cwd . '/bin/quark';

        $origin = $this->cwd . '/vendor/protoqol/quark/bin/quark';
        $target = $this->cwd . '/quark';

        if ($this->fs->exists($origin)) {
            $this->fs->copy($origin, $target, true);
            $this->fs->chmod($target, 0755);

            return (bool)$this->fs->exists($target);
        }

        if ($this->fs->exists($dev_origin)) {
            $this->fs->copy($dev_origin, $target, true);
            $this->fs->chmod($target, 0755);

            return (bool)$this->fs->exists($target);
        }

        return false;
    }
}
