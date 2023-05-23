<?php

return [
    'driver'  => 'sqlite', // mysql ou sqlite
    'host'    => 'localhost', // caso driver seja mysql
    'nomeDB'  => 'teste', // caso driver seja mysql
    'usuario' => 'root',
    'senha'   => '',
    'sqlite'  => 'app/Database/sqlite/default.sqlite', // caso driver seja sqlite
];