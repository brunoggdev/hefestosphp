<?php

if( PHP_VERSION < '8.0.0'){
    die('PHPratico precisa do PHP versão 8.0.0 ou mais alta.');
}

// tomando controle dos errros
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// autoloader simulando o composer autoload
require 'autoloader.php';

// Acessa as constantes do app
require '../app/Config/constantes.php';

// funções auxiliares nativas do PHPratico
require PASTA_RAIZ . 'system/auxiliares.php';

// funções auxiliares do usuário do PHPratico
require PASTA_RAIZ . 'app/auxiliares.php';