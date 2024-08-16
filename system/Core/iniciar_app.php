<?php

define('VERSAO_HEFESTOSPHP', '1.2.4');

if( PHP_VERSION < '8.0.0'){
    die('HefestosPHP precisa do PHP na versão 8.0.0 ou mais alta.');
}

// tomando controle dos errros
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});


// Acessa as constantes do app
require __DIR__ . '/../../app/Config/constantes.php';

date_default_timezone_set(TIMEZONE);

// Carregando autoload do composer caso exista ou do Hefestos caso contrário
if (file_exists($composer_autoload = PASTA_RAIZ . '/vendor/autoload.php')) {
    require $composer_autoload;
}else{
    require 'hefestos_autoloader.php';
}

// funções auxiliares nativas do HefestosPHP
require 'auxiliares.php';

// funções auxiliares do usuário
require PASTA_RAIZ . 'app/auxiliares.php';

require_once PASTA_RAIZ . 'app/Config/inicializacao.php';

// carregar roteador
require PASTA_RAIZ . '/app/Config/rotas.php';