<?php
return <<<EOT
    <?php
    namespace App\Database;
    use System\Database\Tabela;
    /* ----------------------------------------------------------------------
    Você deve retornar a sql para criar uma tabela como desejar.
    Pode ser utilizando a classe Tabela ou mesmo uma string pura.
    ---------------------------------------------------------------------- */

    
    return ( new Tabela('{nome}') )
        ->id();
    EOT;