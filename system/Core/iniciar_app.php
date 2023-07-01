<?php

if( PHP_VERSION < '8.0.0'){
    die('HefestosPHP precisa do PHP na versão 8.0.0 ou mais alta.');
}

// tomando controle dos errros
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Acessa as constantes do app
require '../app/Config/constantes.php';

// Carregando autoload do composer caso exista ou do Hefestos caso contrário
if (file_exists(PASTA_RAIZ . '/vendor/autoload.php')) {
    require PASTA_RAIZ . '/vendor/autoload.php';
}else{
    require 'autoloader.php';
}

// funções auxiliares nativas do HefestosPHP
require 'auxiliares.php';

// funções auxiliares do usuário do HefestosPHP
require PASTA_RAIZ . 'app/auxiliares.php';