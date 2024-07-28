<?php
return <<<EOT
    <?php

    use Hefestos\Database\Tabela;

    return ( new Tabela('{nome}') )
        ->id();
    EOT;