<?php

define('PASTA_RAIZ', __DIR__ . '/../');
define('PASTA_PUBLIC', PASTA_RAIZ . 'public/');


if( PHP_VERSION < '8.0.0'){
    die('HefestosPHP precisa do PHP na versão 8.0.0 ou mais alta.');
}



// tomando controle dos errros
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});



// Carregando autoload do composer caso exista ou do Hefestos caso contrário
if (file_exists($composer_autoload = PASTA_RAIZ . '/vendor/autoload.php')) {
    require $composer_autoload;
}else{
    require 'hefestos_autoloader.php';
}



// funções auxiliares nativas do HefestosPHP
require 'auxiliares.php';


define('AMBIENTE', config('app.ambiente'));
define('URL_BASE', config('app.url_base'));
define('VERSAO_APP', config('app.versao_app'));
define('TIMEZONE', config('app.timezone'));
define('MANUTENCAO', config('app.manutencao'));

date_default_timezone_set(TIMEZONE);



// funções auxiliares do usuário
require PASTA_RAIZ . 'app/auxiliares.php';

// Configurações extras opcionais do usuário
require_once PASTA_RAIZ . 'app/Config/inicializacao.php';

// carregar roteador
require PASTA_RAIZ . '/app/Config/rotas.php';