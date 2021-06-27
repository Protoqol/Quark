<?php


if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?: $default;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }

        die();
    }
}
