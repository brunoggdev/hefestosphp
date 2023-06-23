<?php
$rotas = new \Hefestos\Rotas\Roteador();
$rotas->namespacePadrao('\App\Controllers');
/* ----------------------------------------------------------------------
Cada rota deve ser respondida com o retorno de uma função, seja ela uma
função anonima ou um metodo de controller. Consulte a documentação.
---------------------------------------------------------------------- */


$rotas->agrupar('filtro', function() use ($rotas){
    $rotas->get('/route', 'controller');
});