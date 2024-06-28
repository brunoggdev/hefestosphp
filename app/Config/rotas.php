<?php
$rotas = new \Hefestos\Rotas\Roteador();
/* ----------------------------------------------------------------------
Cada rota deve ser respondida com o retorno de uma função, seja ela uma
função anonima ou um metodo de controller. Consulte a documentação.
---------------------------------------------------------------------- */

$rotas->get('/', function() {
    return view('home');
});

$rotas->get('/demo', function() {
    return montarPagina('demo');
});

$rotas->get('/outra-demo', function() {
    return json([
        'cor' => 'success',
        'texto' => 'Olá do backend!'
    ]);
});
