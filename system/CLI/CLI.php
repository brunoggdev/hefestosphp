<?php

namespace System\CLI;

class CLI
{
    /**
     * Mapeia o comando recebido para uma função correspondente
    */
    public function __construct(array $comando)
    {
        match ($comando[1]) {
            'iniciar', 'servir', 'serve' => $this->iniciar($comando[2] ?? '8080'),
            'criar', 'fazer', 'gerar' => $this->criar($comando[2]??'', $comando[3]??''),
            'testar' => $this->testar($comando[2]??''),
            'ajuda' => $this->ajuda(),
            default => $this->imprimir("Você precisa informar algum comando válido.\n# Tente usar 'php forja ajuda'."),
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
            $this->imprimir("Ex.: php forja criar Model Usuario");
            exit;

        }

        if( empty($nome) ){

            $this->imprimir("Você deve informar um nome pro arquivo depois do tipo.", 0);
            $this->imprimir("Ex.: php forja criar $arquivo Usuarios$arquivo.");
            exit;

        }


        $arquivo = ucfirst($arquivo);
        $nome = ucfirst($nome);

        $caminho = match ($arquivo) {
            'Controller' =>  PASTA_RAIZ . 'app/Controllers/',
            'Model' => PASTA_RAIZ . 'app/Models/',
            'Filtro' => PASTA_RAIZ . 'app/Filtros/',
        };


        $template = require "templates/$arquivo.php";


        if ( file_put_contents("$caminho$nome.php", str_replace('{nome}', $nome, $template)) ) {
            $resposta = "$arquivo $nome criado com sucesso.";
        } else {
            $resposta = "Algo deu errado ao gerar o $arquivo.";
        }

        $this->imprimir($resposta);
    }



    /**
    * Roda todas as closures de teste e imprime seus resultados
    * @author Brunoggdev
    */
    public function testar(string $caminho):void
    {
        $caminho = 'app/Testes/' . $caminho;
        // tomando controle dos erros nativos do php
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        // Se for um diretorio, busque todos os arquivos dentro
        if( is_dir($caminho) ){
            $arquivos = array_merge(
                glob($caminho . '*.php'),
                glob($caminho . '**/*.php')
            );

            foreach ($arquivos as $arquivo) {
                require_once $arquivo;
            }

        }else{
            // se não, busque apenas o arquivo informado
            try{
                require_once $caminho;
            }catch(\ErrorException){
                $this->imprimir('Arquivo não encontrado.');
                exit;
            }
        }

        $testesPassaram = 0;
        $testesFalhaaram = 0;
        foreach ($testar->testes() as $i => $teste) {

            try {
                $resultado = call_user_func($teste['funcao']);
            } catch (\Throwable $th) {
                $resultado = false;
                $erro = 
                "-> \033[1m Erro encontrado: \033[0m" . $th->getMessage() . "\n" . 
                "  -> \033[1m Na linha: \033[0m" . $th->getLine() . "\n" . 
                "  -> \033[1m Do arquivo: \033[0m" . $th->getFile() . "\n\n";
            }

            if($resultado === true){
                $status = "\033[42mPassou.\033[0m";
                $testesPassaram++;
            }else{
                $status = "\033[41mFalhou.\033[0m";
                $testesFalhaaram++;
            }



            $trilha = str_repeat('.', 80 - mb_strlen($teste['descricao']) - mb_strlen($status));

            $relatorio = sprintf("%d - %s %s %s", ($i+1), "Testa se $teste[descricao]", $trilha, $status);
            
            $this->imprimir($relatorio, isset($erro) ? 0 : 1);

            if(isset($erro)){
                $this->imprimir($erro, 0);
                unset($erro);
            }
        }
        echo "\n";
        $this->imprimir("Passaram: $testesPassaram.", 0);
        $this->imprimir("Falharam: $testesFalhaaram.");
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
        $this->imprimir('-------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('| Comandos |           Parametros                |                  Exemplos                    |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  inciar  | porta (opcional, 8080 padrão)       | php forja iniciar (8888)                   |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  criar   | [controller, model, filtro] + nome  | php forja criar controller NotasController |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  testar  | pasta/arquivo especifico (opcional) | php forja testar (HefestosPHP)             |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------');
    }
}