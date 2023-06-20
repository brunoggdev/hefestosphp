<?php
$rota = new \Hefestos\Rotas\Roteador();
$rota->namespacePadrao('\App\Controllers');
/* ----------------------------------------------------------------------
Cada rota deve ser respondida com o retorno de uma função, seja ela uma
função anonima ou um metodo de controller. Consulte a documentação.
---------------------------------------------------------------------- */


$rota->get('/', function(){
    return view('home');
});