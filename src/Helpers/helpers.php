<?php

use Protoqol\Quark\Quark;

if (!function_exists('env')) {
    /**
     * Get variable from .quark-env.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed|null
     */
    function env(string $key, $default = null)
    {
        if (in_array($key, $_ENV, true)) {
            return $_ENV[$key] ?: $default;
        }

        return $default;
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die.
     *
     * @param ...$vars
     */
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            \Symfony\Component\VarDumper\VarDumper::dump($var);
        }

        die();
    }
}

if (!function_exists('stylisedWriteLnOutput')) {
    /**
     * Get formatted console output string.
     *
     * @param string $output
     * @param string $color
     *
     * @return string[]
     */
    function stylisedWriteLnOutput(string $output, string $color = 'green'): array
    {
        return [
            "<options=bold;fg={$color};>$output</>",
        ];
    }
}

if (!function_exists('meta')) {
    /**
     * Get key from internal config.
     *
     * @param string $key
     * @param $default
     *
     * @return false|mixed|string
     */
    function meta(string $key, $default = null)
    {
        return (new Quark)->getMetaKey($key, $default);
    }
}

if (!function_exists('quark')) {
    // If not in CLI, the singleton will need to be instantiated somewhere besides `bin/quark`.
    // @TODO Find better place for instantiating Quark when not in CLI.
    // if (!defined('STDIN') && !array_key_exists('QUARK', $GLOBALS) && !$GLOBALS['QUARK'] instanceof \Protoqol\Quark\Quark) {
    $GLOBALS['QUARK'] = new Quark;
    // }

    /**
     * Get Quark singleton instance.
     *
     * @return Quark
     */
    function quark(): Protoqol\Quark\Quark
    {
        return $GLOBALS['QUARK'];
    }

}
