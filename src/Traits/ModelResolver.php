<?php


namespace Protoqol\Quark\Traits;


use Protoqol\Quark\Helpers\Str;

trait ModelResolver
{
    /**
     * Resolve table name.
     *
     * @return string
     */
    public function resolveTableName(): string
    {
        $particles = explode('\\', static::class);

        return Str::pluralize(strtolower($particles[count($particles) - 1]));
    }
}
