<?php

if( PHP_VERSION < '8.0.0'){
    die('HefestosPHP precisa do PHP na versão 8.0.0 ou mais alta.');
}

// tomando controle dos errros
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Carregue composer se existir e configure o namespace hefestos
// do contrário carregue o autoloader do hefestos 
if (file_exists('../../vendor/autoload.php')) {
    $composer_autoloader = require '../../vendor/autoload.php';
    $composer_autoloader->setPsr4('hefestos\\', 'system/');
}else{
    require 'autoloader.php';
}

// Acessa as constantes do app
require '../app/Config/constantes.php';

// funções auxiliares nativas do HefestosPHP
require 'auxiliares.php';

// funções auxiliares do usuário do HefestosPHP
require PASTA_RAIZ . 'app/auxiliares.php';