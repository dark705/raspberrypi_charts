<?php
require_once '../vendor/autoload.php';

spl_autoload_register(function($class){
    $file = '../php/'. str_replace('\\', '/', $class) . '.php';
    if(file_exists($file)){
        require_once($file);
    }
});
