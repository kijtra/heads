<?php
include(__DIR__.'/src/functions.php');

spl_autoload_register(function($className) {
    $prefix = 'Kijtra\\';
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) !== 0) {
        return;
    }

    $relativeClass = substr($className, $len);
    $file = rtrim($baseDir, '/').'/'.str_replace('\\', '/', $relativeClass).'.php';

    if (file_exists($file)) {
        require $file;
    }
});
