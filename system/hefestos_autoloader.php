<?php
// PSR-4 Autoloader nativo do HefestosPHP

spl_autoload_register(function ($classe) {

    $classe = lcfirst( str_replace('\\', DIRECTORY_SEPARATOR, $classe) );

    if (str_starts_with($classe, 'hefestos')) {
        $classe = str_replace('hefestos', 'system', $classe);
    }

    require_once PASTA_RAIZ . "$classe.php";
});
