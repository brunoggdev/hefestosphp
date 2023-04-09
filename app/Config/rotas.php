<?php

use App\Controllers\PaginasController;

$rota = new \System\Rotas\Roteador();
$rota->namespacePadrao('\App\Controllers');
# ----------------------------------------------------------------------
# Configure abaixo suas rotas. O objeto "$rota" pode ser utilizado:
# $rota->[verbohttp]('/usuario/{param}', 'Controller::metodo');
# Para usar uma coringa (wildcard), use {param} ente barras da url
# $rota->[verbohttp]('uri', [Controller::class, 'metodo']);
# $rota->[verbohttp]('uri', function(){});
# ----------------------------------------------------------------------


$rota->get('/', 'PaginasController::index');

// mapeia qualquer rota para um arquivo de mesmo nome (útil para páginas estaticas)
$rota->get('{param}', 'PaginasController::mostrar');