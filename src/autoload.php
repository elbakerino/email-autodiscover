<?php

//
// Only need to be included when not installed through composer
//

spl_autoload_register(function($class) {
    $prefix = 'Bemit\Autodiscover\\';
    $prefix_length = strlen($prefix);
    if(strncmp($prefix, $class, $prefix_length) !== 0) {
        return;
    }
    $src_path = '';
    $class_path = __DIR__ . '/' . $src_path . str_replace('\\', '/', substr($class, $prefix_length)) . '.php';
    if(file_exists($class_path)) {
        require $class_path;
    }
});