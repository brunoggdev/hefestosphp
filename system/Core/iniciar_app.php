<?php

if( PHP_VERSION < '8.0.0'){
    die('HefestosPHP precisa do PHP na versão 8.0.0 ou mais alta.');
}

// tomando controle dos errros
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

define('VERSAO_HEFESTOSPHP', 1.1);

// Acessa as constantes do app
require __DIR__ . '/../../app/Config/constantes.php';

// Carregando autoload do composer caso exista ou do Hefestos caso contrário
if (file_exists($composer_autoload = PASTA_RAIZ . '/vendor/autoload.php')) {
    require $composer_autoload;
}else{
    require 'hefestos_autoloader.php';
}

// funções auxiliares nativas do HefestosPHP
require 'auxiliares.php';

// funções auxiliares do usuário do HefestosPHP
require PASTA_RAIZ . 'app/auxiliares.php';