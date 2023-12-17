<?php

if (!function_exists('dd')) {
    /**
     * @return never
     */
    function dd(...$vars)
    {
        if (!in_array(\PHP_SAPI, ['cli', 'phpdbg'], true) && !headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        foreach ($vars as $v) {
            var_dump($v);
        }

        exit(1);
    }
}