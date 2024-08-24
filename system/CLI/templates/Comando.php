<?php

return <<<EOT
    <?php

    namespace App\Comandos{namespace};

    class {nome}
    {
        /**
         * Esse é o metodo que será chamado quando o comando for invocado pela linha de comando.
         * O metodo receberá como parâmetro, respectivamente, os argumentos informados na invocação do comando.
         */
        public function executar()
        {
            //codigo
        }
    }
    EOT;