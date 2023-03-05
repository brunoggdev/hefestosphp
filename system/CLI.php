<?php

class CLI
{
    /**
     * Mapeia o comando recebido para uma função correspondente
    */
    public function __construct(array $comando)
    {
        match ($comando[1]) {
            'iniciar' => $this->iniciar($comando[2] ?? '8080'),
            'criar' => $this->criar($comando[2]??'', $comando[3]??''),
            'ajuda' => $this->ajuda(),
            default => $this->imprimir("Você precisa informar algum comando.\n# Tente usar 'php pratico ajuda'."),
        };
    }


    
    /**
    * Inicia um servidor embutido do PHP para a pasta public 
    * na porta desejada (padrão 8080)
    * @author Brunoggdev
    */
    private function iniciar(string $porta)
    {
        exec("php -S localhost:$porta -t public");
    }



    /**
    * Cria um novo arquivo com as propriedades desejadas
    * @author Brunoggdev
    */
    private function criar(string $arquivo, string $nome)
    {
        $arquivo = ucfirst($arquivo);
        $nome = ucfirst($nome);

        if( empty($arquivo) ){

            $this->imprimir("Você deve informar um tipo de arquivo para ser gerado (controller ou model)", 0);
            $this->imprimir("Ex.: php pratico fazer Model Usuario");
            exit;

        }

        if( empty($nome) ){

            $this->imprimir("Você deve informar um nome pro arquivo depois do tipo.", 0);
            $this->imprimir("Ex.: php pratico fazer $arquivo Usuarios$arquivo.");
            exit;

        }


        require 'app/Config/constantes.php';

        $caminho = match ($arquivo) {
            'Controller' =>  BASE_PATH . 'app/Controllers/',
            'Model' => BASE_PATH . 'app/Models/',
        };

        $base = $arquivo . 'Base';
        $namespace = 'App\\' . $arquivo . 's';
        
        $template = <<<EOT
        <?php

        namespace $namespace;

        class $nome extends $base
        {
            // Seus metodos de controller aqui
        }

        EOT;


        if ( file_put_contents("$caminho$nome.php", $template) ) {
            $resposta = "$arquivo criado sucesso.";
        } else {
            $resposta = "Algo deu errado ao gerar o $arquivo.";
        }

        $this->imprimir($resposta);
    }



    /**
    * Imprime a resposta desejada no terminal
    * @author Brunoggdev
    */
    private function imprimir(string $resposta, int $eol = 2)
    {
        echo "\n# $resposta" . str_repeat(PHP_EOL, $eol);
    }



    /**
    * Imprime uma sessão de ajuda listando os 
    * comandos disponíveis e como usa-los;
    * @author Brunoggdev
    */
    private function ajuda()
    {
        $this->imprimir('-------------------------------------------------------------------------------------------', 0);
        $this->imprimir('| Comandos |           Parametros          |                  Exemplos                    |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  inciar  | porta (opcional, 8080 padrão) | php pratico iniciar                          |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  fazer   | [controller ou model] + nome  | php pratico fazer controller NotasController |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------', 0);
    }
}