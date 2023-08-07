<?php

namespace App\Database;

use Hefestos\Database\Tabela;

return ( new Tabela('pets') )
    ->id()
    ->string('nome')
    ->int('dono')
    ->boolean('vip')
    ->datetime('data_cadastro')
    ->foreignKey('dono', 'usuarios', 'id');