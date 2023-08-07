<?php
return <<<EOT
    <?php
    
    namespace App\Database;

    use Hefestos\Database\Tabela;

    return ( new Tabela('{nome}') )
        ->id();
    EOT;