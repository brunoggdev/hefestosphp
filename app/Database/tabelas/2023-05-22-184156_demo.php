<?php

namespace App\Database;

use Hefestos\Database\Tabela;

return ( new Tabela('demo') )
    ->id()
    ->string('col_demo');
