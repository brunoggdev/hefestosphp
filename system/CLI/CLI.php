<?php

namespace Hefestos\CLI;

class CLI
{
    /**
     * Mapeia o comando recebido para uma função correspondente
    */
    public function __construct(array $comando)
    {
        match ($comando[1]??false) {
            'iniciar', 'servir', 'serve' => $this->iniciar($comando[2] ?? '8080'),
            'criar', 'fazer', 'gerar' => $this->criar($comando[2]??'', $comando[3]??'', $comando[4]??false),
            'migrar' => $this->migrar($comando[2]??''),
            'fornalha', 'soldar', 'forjar', 'brincar' => $this->fornalha(),
            'testar' => $this->testar($comando[2]??''),
            'ajuda'=> $this->ajuda(),
            default => [$this->imprimir("Você precisa informar algum comando válido."), $this->ajuda()],
        };
    }


    
    /**
    * Inicia um servidor embutido do PHP para a pasta public 
    * na porta desejada (padrão 8080)
    * @author Brunoggdev
    */
    private function iniciar(string $porta):void
    {
        echo("\n\033[92m# Servidor de desenvolvimento do HefestosPHP iniciando em http://localhost:$porta.\n");
        echo("\033[93m# Pressione ctrl+c para interromper.\033[0m\n");
        exec("php -S localhost:$porta -t public");
    }



    /**
    * Cria um novo arquivo com as propriedades desejadas
    * @author Brunoggdev
    */
    private function criar(string $tipo_arquivo, string $nome, string|false $controllerRecurso = false):void
    {
        if( empty($tipo_arquivo) ){
            echo("\n\033[93m# Qual tipo de arquivo deseja criar? [controller, model, filtro ou tabela].\033[0m\n\n");
            $tipo_arquivo = readline('> ');
        }

        if( $tipo_arquivo == 'controller' &&  $controllerRecurso != '--recurso' ){
            echo("\n\033[93m# Deseja que o controller já contenha todos os metodos http de recurso rest? [y/n]\033[0m\n\n");
            if((! in_array(readline('> '), ['n', 'no', 'nao'] ))){
                $controllerRecurso = '--recurso';
            }
        }

        if( empty($nome) ){
            echo("\n\033[93m# Qual nome do(a) $tipo_arquivo?.\033[0m\n\n");
            $nome = readline('> ');
        }

        $tipo_arquivo = ucfirst($tipo_arquivo);
        if($tipo_arquivo !== 'Tabela'){
            $nome = ucfirst($nome);
        }

        $caminho = match ($tipo_arquivo) {
            'Controller' =>  PASTA_RAIZ . 'app/Controllers/',
            'Model' => PASTA_RAIZ . 'app/Models/',
            'Filtro' => PASTA_RAIZ . 'app/Filtros/',
            'Tabela' => PASTA_RAIZ . 'app/Database/tabelas/',
            default => die("\n\033[91m# Tipo de arquivo '$tipo_arquivo' não suportado.\033[0m")
        };
        $template = ($tipo_arquivo == 'Controller' && $controllerRecurso == '--recurso') ? 'ControllerRecurso' : $tipo_arquivo;

        $template = require "templates/$template.php";
        if($tipo_arquivo == 'Model'){
            $tabela = str_ends_with($tabela = strtolower($nome), 'model') ? substr($tabela, 0, -5) : $tabela;
            $arquivo = str_replace(['{nome}', '{tabela}'], [$nome, $tabela], $template);
        }else{
            $arquivo = str_replace('{nome}', $nome, $template);
        }

        if($tipo_arquivo === 'Tabela'){
            $nome = date('Y-m-d-His_') . $nome;
        }

        if ( file_put_contents("$caminho$nome.php", $arquivo) ) {
            $resposta = "\n\033[92m# $tipo_arquivo $nome criado com sucesso.\n\033[0m";
        } else {
            $resposta = "\n\033[91m# Algo deu errado ao gerar o $tipo_arquivo.\n";
        }

        echo($resposta);
    }


    /**
     * Inicia um idle repl do PHP no terminal
     * @author Brunoggdev
    */
    public function fornalha():void
    {
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        echo "\n\033[92m# Fornalha iniciada - Ambiente interativo do HefestosPHP.\033[0m";
        echo "\n\033[93m# Pressione ctrl+c para sair.\033[0m";
        while (true) {
            echo "\n\n";
            try {
                $entrada = readline("Fornalha > ");
                echo PHP_EOL;

                if(preg_match('/(echo|return|var_dump|print_r)/', $entrada) !== 1){
                    $entrada = 'return ' . $entrada;
                }

                $saida = eval($entrada);
                isset($saida) && var_export($saida);
            } catch (\Throwable $th) {
                echo 
                "\033[91m -> Erro encontrado: \033[0m" . $th->getMessage() . "\n" . 
                "\033[91m -> Na linha: \033[0m" . $th->getLine() . "\n" . 
                "\033[91m -> Do arquivo: \033[0m" . $th->getFile();
            }
        }
    }



    /**
    * Executa todas as closures de teste e imprime seus resultados
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
        echo "\n";
        
        // $testar é a instancia presente nos testes do usuario
        $testador = new \Hefestos\Testes\Testador($testar);
        $testesPassaram = 0;
        $testesFalhaaram = 0;
        foreach ($testador->testes() as $i => $teste) {

            try {
                $resultado = $testador->testar($teste['funcao']->bindTo($testador));
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
        $this->imprimir("\033[92mPassaram:\033[0m $testesPassaram.", 0);
        $this->imprimir("\033[91mFalharam:\033[0m $testesFalhaaram.");
    }


    /**
    * Executa as sql's de criação de tabelas
    * @author Brunoggdev
    */
    public function migrar(string $caminho):void
    {
        $caminho = 'app/Database/' . $caminho;

        // Se for um diretorio, busque todos os arquivos dentro
        if( is_dir($caminho) ){
            $tabelas = array_merge(
                glob($caminho . '*.php'),
                glob($caminho . '**/*.php')
            );

            // Executa a sql retornada por cada arquivo
            foreach ($tabelas as $tabela) {
                $sql = (string) require $tabela;

                if (stripos($sql, 'CREATE TABLE') !== 0){
                    throw new \Exception('Sql informada não é válida para esta operação.');
                }

                (new \Hefestos\Database\Database)->query($sql);
            }

        }else{
            // se não, busque apenas o arquivo informado
            try{
                $sql = (string) require $caminho;

                if (stripos($sql, 'CREATE TABLE') !== 0){
                    throw new \Exception('Sql informada não é válida para esta operação.');
                }

                (new \Hefestos\Database\Database)->query($sql);
            }catch(\ErrorException){
                $this->imprimir('Arquivo não encontrado.');
                exit;
            }
        }

        echo "\n\033[92m# Tabela(s) criada(s) com sucesso!\033[0m";
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
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('| Comandos |                 Parametros                  |                  Exemplos                  |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  inciar  | porta (opcional, 8080 padrão)               | php forja iniciar (8888)                   |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  criar   | [controller, model, filtro, tabela] + nome  | php forja criar controller NotasController |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  testar  | pasta/arquivo especifico (opcional)         | php forja testar (HefestosPHP)             |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  migrar  | pasta/arquivo especifico (opcional)         | php forja migrar (usuarios.php)            |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------');
    }
}