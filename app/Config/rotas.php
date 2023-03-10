<?php
$rota = new \System\Core\Classes\Roteador();
# ----------------------------------------------------------------------
# Configure abaixo suas rotas. O objeto "$rota" pode ser utilizado assim:
# $rota->[verbohttp]('uri', 'Controller::metodo');
# Para usar uma coringa (wildcard), use {param} ente barras da url
# $rota->[verbohttp]('uri', [Controller::class, 'metodo']);
# $rota->[verbohttp]('uri', function(){});
# ----------------------------------------------------------------------


$rota->get('/', 'PaginasController::index');
$rota->get('/teste/testando', 'PaginasController::index');
$rota->get('/t/{param}/bb/{param}', 'PaginasController::teste');