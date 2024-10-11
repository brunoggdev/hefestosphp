<?php
/* ----------------------------------------------------------------------
Cada rota deve ser respondida com o retorno de uma função, seja ela uma
função anonima ou um metodo de controller. Consulte a documentação.
---------------------------------------------------------------------- */

use Hefestos\Rotas\Rota;

Rota::get('/', function() {
    return view('home');
});

Rota::get('/demo', function() {
    return montarPagina('demo');
});

Rota::get('/outra-demo', function() {
    return json([
        'cor' => 'success',
        'texto' => 'Olá do backend!'
    ]);
});
