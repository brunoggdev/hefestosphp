<?php

if (isset($argc)) {

    match ($argv[1]) {
        'serve' => exec('php -S localhost:8080 -t public'),
        'fazer' => fazer($argv[2])
    };
    
}


/**
* Cria um novo arquivo com as propriedades desejadas
* @author Brunoggdev
*/
function fazer($arquivo)
{
    // TODO: Gerar o arquivo desejado.
    echo $arquivo;
}