<?php

namespace System;

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
            'testar' => $this->testar(),
            'ajuda' => $this->ajuda(),
            default => $this->imprimir("Você precisa informar algum comando.\n# Tente usar 'php pratico ajuda'."),
        };
    }


    
    /**
    * Inicia um servidor embutido do PHP para a pasta public 
    * na porta desejada (padrão 8080)
    * @author Brunoggdev
    */
    private function iniciar(string $porta):void
    {
        exec("php -S localhost:$porta -t public");
    }



    /**
    * Cria um novo arquivo com as propriedades desejadas
    * @author Brunoggdev
    */
    private function criar(string $arquivo, string $nome):void
    {
        if( empty($arquivo) ){

            $this->imprimir("Você deve informar um tipo de arquivo para ser gerado (controller ou model)", 0);
            $this->imprimir("Ex.: php pratico criar Model Usuario");
            exit;

        }

        if( empty($nome) ){

            $this->imprimir("Você deve informar um nome pro arquivo depois do tipo.", 0);
            $this->imprimir("Ex.: php pratico criar $arquivo Usuarios$arquivo.");
            exit;

        }


        $arquivo = ucfirst($arquivo);
        $nome = ucfirst($nome);

        $caminho = match ($arquivo) {
            'Controller' =>  PASTA_RAIZ . 'app/Controllers/',
            'Model' => PASTA_RAIZ . 'app/Models/',
        };

        $base = $arquivo . 'Base';
        $namespace = 'App\\' . $arquivo . 's';
        $tabela = $arquivo === 'Model' ? 'protected $tabela = \'\';' : '';

        $template = <<<EOT
        <?php

        namespace $namespace;

        class $nome extends $base
        {
            $tabela
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
    * Roda todas as closures de teste e imprime seus resultados
    * @author Brunoggdev
    */
    public function testar():void
    {
        require PASTA_RAIZ . 'app/testes/testes.php';

        $verde = "\033[42m";
        $vermelho = "\033[41m";
        $resetaCor = "\033[0m";

        foreach ($testar->testes() as $teste) {
            
            $numeroDePontos = 80 - strlen($teste['descricao']) - 1;
            $status = "$vermelho Falhou.$resetaCor";

            if(call_user_func($teste['funcao']) ){
                $status = "$verde Passou.$resetaCor";
            }

            printf(
                "%s %s %s %s\n", 
                $teste['descricao'], 
                str_repeat(".", $numeroDePontos), 
                $status, 
                "\n"
            );
        }
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
        $this->imprimir('|  criar   | [controller ou model] + nome  | php pratico criar controller NotasController |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------', 0);
    }
}