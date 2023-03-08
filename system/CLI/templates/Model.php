<?php
return <<<EOT
    <?php

    namespace App\Models;

    class {nome} extends ModelBase
    {
        // tabela do banco de dados ao qual o model está relacionado
        protected \$tabela = '';
    }

    EOT;