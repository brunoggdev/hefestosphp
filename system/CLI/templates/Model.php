<?php
return <<<EOT
    <?php

    namespace App\Models;

    use Hefestos\ModelBase;
    
    class {nome} extends ModelBase
    {
        // tabela do banco de dados ao qual o model está relacionado
        protected \$tabela = '{tabela}';
    }
    EOT;