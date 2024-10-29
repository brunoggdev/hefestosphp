<?php
$rotas = \Hefestos\Rotas\Rota::instancia();
/* ----------------------------------------------------------------------
Cada rota deve ser respondida com o retorno de uma função, seja ela uma
função anonima ou um metodo de controller. Consulte a documentação.
---------------------------------------------------------------------- */

$rotas->get('/', function() {
    return view('home');
});