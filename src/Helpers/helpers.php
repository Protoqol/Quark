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

if (!function_exists('dd')) {
    function dump(...$vars)
    {
        foreach ($vars as $var) {
            var_dump($var);
        }
    }
}

if (!function_exists('stylisedWriteLnOutput')) {
    function stylisedWriteLnOutput(string $output): array
    {
        return [
            '<options=bold;fg=green;>' . $output . '</>',
        ];
    }
}
