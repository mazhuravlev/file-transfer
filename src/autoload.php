<?php

spl_autoload_register(
    function ($class) {
        $classFile = __DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
        if(file_exists($classFile)) {
            require_once $classFile;
            return true;
        } else {
            return false;
        }
    }
);