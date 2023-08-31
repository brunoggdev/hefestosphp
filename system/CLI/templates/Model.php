<?php
return <<<EOT
    <?php

    namespace App\Models;

    use Hefestos\Model;
    
    class {nome} extends Model
    {
        // tabela do banco de dados ao qual o model está relacionado
        protected string \$tabela = '{tabela}';
    }
    EOT;