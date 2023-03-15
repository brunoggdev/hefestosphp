<?php

$rota = new \System\Core\Classes\Roteador();
// use App\Controllers\PaginasController;
$rota->namespacePadrao('\App\Controllers');

# ----------------------------------------------------------------------
# Configure abaixo suas rotas. O objeto "$rota" pode ser utilizado assim:
# Para usar uma coringa (wildcard), use {param} ente barras da url
# $rota->[verbohttp]('/usuario/{param}', 'Controller::metodo');
# $rota->[verbohttp]('uri', [Controller::class, 'metodo']);
# $rota->[verbohttp]('uri', function(){});
# ----------------------------------------------------------------------


$rota->get('/', 'PaginasController::index');
$rota->post('/teste1', 'PaginasController::teste');
$rota->get('/teste2', [PaginasController::class, 'index']);
$rota->get('/teste3/{param}', function($teste){
    $retorno = ['teste' => 'sou muito bom cara slc'];
    if(isset($teste)){
        $retorno['teste2'] = $teste;
    }
    return json($retorno);
});
$rota->get('/t/{param}/bb/{param}', 'PaginasController::teste');