<?php

if( PHP_VERSION < '8.0.0'){
    die('HefestosPHP precisa do PHP na versão 8.0.0 ou mais alta.');
}

// tomando controle dos errros
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// autoloader simulando o composer autoload
require 'autoloader.php';

// Acessa as constantes do app
require '../app/Config/constantes.php';

// funções auxiliares nativas do HefestosPHP
require PASTA_RAIZ . 'system/auxiliares.php';

// funções auxiliares do usuário do HefestosPHP
require PASTA_RAIZ . 'app/auxiliares.php';