<?php

spl_autoload_register(function ($class) {
    
    $class = strtolower( str_replace('\\', DIRECTORY_SEPARATOR, $class) );

    require PASTA_RAIZ . "$class.php";
});