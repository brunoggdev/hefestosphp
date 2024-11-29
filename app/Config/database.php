<?php
/* ----------------------------------------------------------------------
Aqui devem ser informados os dados da conexão com a base de dados.
Note que mais de uma conexão pode ser configurada, onde a chave é o nome
do ambiente (desenvolvimento, producao ou outro definido por você).
---------------------------------------------------------------------- */

return [

    'producao' => [
        'driver'  => 'mysql', // mysql ou sqlite
        'host'    => 'localhost', // caso driver seja mysql
        'nome_db'  => 'olimpo', // caso driver seja mysql
        'usuario' => 'root',
        'senha'   => 'senha_segura_123',
        'sqlite'  => '', // caso driver seja sqlite
    ],


    'desenvolvimento' => [
        'driver'  => 'sqlite', // mysql ou sqlite
        'sqlite'  => 'app/Database/sqlite/banco_de_dados.sqlite', // caso driver seja sqlite
        'host'    => '', // caso driver seja mysql
        'nome_db'  => '', // caso driver seja mysql
        'usuario' => '', // caso driver seja mysql
        'senha'   => '', // caso driver seja mysql
    ]
];
