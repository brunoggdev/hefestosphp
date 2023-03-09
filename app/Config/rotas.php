<?php
$rota = new \System\Core\Roteador();
# ----------------------------------------------------------------------
# Configure abaixo suas rotas. O objeto "$rota" pode ser utilizado assim:
# $rota->[verbohttp]('uri', 'Controller::metodo');
# $rota->[verbohttp]('uri', [Controller::class, 'metodo']);
# $rota->[verbohttp]('uri', function(){});
# ----------------------------------------------------------------------


$rota->get('/asd', 'PaginasController::index');
$rota->get('/', 'PaginasController::index');