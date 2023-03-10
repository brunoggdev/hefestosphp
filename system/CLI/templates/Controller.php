<?php

return <<<EOT
    <?php

    namespace App\Controllers;

    class {nome} extends ControllerBase
    {
        public function index():string|Redirecionar
        {
            // return view();
            // ou
            // return json();
            // ou ainda
            // return redirecionar(); 
        }
    }

    EOT;