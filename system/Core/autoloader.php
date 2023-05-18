<?php

spl_autoload_register(function ($class) {
    
    $class = lcfirst( str_replace('\\', DIRECTORY_SEPARATOR, $class) );

    require PASTA_RAIZ . "$class.php";
});
