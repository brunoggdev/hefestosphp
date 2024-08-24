<?php

namespace Hefestos\CLI;

use Hefestos\Ferramentas\Cronometro;
use Hefestos\Testes\SuiteDeTestes;
use Hefestos\Testes\Testador;

class CLI
{
    /**
     * Mapeia o comando recebido para uma função correspondente
    */
    public function __construct(array $comando)
    {
        match ($comando[1]??false) {
            'c', 'custom', 'u', 'usuario' => $this->comandosDoUsuario(array_slice($comando, 2)),
            'iniciar', 'servir', 'serve' => $this->iniciar(),
            'criar', 'forjar', 'fazer', 'gerar' => $this->criar($comando[2]??'', $comando[3]??'', $comando[4]??false),
            'migrar' => $this->migrar($comando[2]??'tabelas', $comando[3]??''),
            'fornalha', 'soldar', 'brincar' => $this->fornalha(),
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
    private function iniciar():void
    {
        $url = URL_BASE;

        echo("\n\033[92m# Servidor de desenvolvimento do HefestosPHP deve ser iniciado em http://$url.\n");
        echo("\033[93m# Pressione ctrl+c para interromper.\033[0m\n");
        exec("php -S $url -t ". '"'. PASTA_PUBLIC . '"');
    }



    /**
    * Cria um novo arquivo com as propriedades desejadas
    * @author Brunoggdev
    */
    private function criar(string $tipo_arquivo, string $nome, string|false $flags = false):void
    {
        if ($tipo_arquivo == 'composer') {
            echo("\n\033[93m# Atenção: Caso já tenha um arquivo \"composer.json\" na raiz do projeto ele será reescrito.\033[0m\n");
            echo("\n# Confirma que possui composer está instalado e deseja habilita-lo no hefestos? [s/n]\n\n");

            if((in_array(readline('> '), ['y', 'yes', 's', 'sim', 'Y', 'YES', 'S', 'SIM'] ))){
                $this->habilitarComposer();
            }else{
                echo("\n\033[91m# Nada foi feito...\033[0m\n\n");
            }

            return;
        }

        if( empty($tipo_arquivo) ){
            echo("\n\033[93m# Qual tipo de arquivo deseja criar? [controller, model, filtro, tabela, entidade, comando ou js].\033[0m\n\n");
            $tipo_arquivo = readline('> ');
        }

        if (! in_array(strtolower($tipo_arquivo), ['controller', 'model', 'filtro', 'tabela', 'js', 'entidade', 'comando']) ) {
            echo("\n\033[93m# O tipo de arquivo informado não parece válido. Qual deseja criar? [controller, model, filtro, tabela, entidade, comando ou js].\033[0m\n\n");
            $tipo_arquivo = readline('> ');
            $this->criar($tipo_arquivo, $nome, $flags);
            return;
        }


        // Processar flags
        if ($flags) {
            $flags_ = str_split(trim($flags, '-'));

            foreach ($flags_ as $flag) {

                $novo_arquivo = match (true) {
                    $flag == 't' && $tipo_arquivo != 'tabela' => ['tabela', $nome],
                    $flag == 'm' && $tipo_arquivo != 'model' => ['model', str_ends_with($nome = strtolower($nome), 'model') ? $nome : $nome.'Model'],
                    $flag == 'c' && $tipo_arquivo != 'controller' => ['controller', str_ends_with($nome = strtolower($nome), 'controller') ? $nome : $nome.'Controller'],
                    default => false
                };

                if ($novo_arquivo) {
                    $this->criar(...$novo_arquivo);
                }
                
                
            }
        }


        // PERGUNTA AO USUÁRIO SE DEVE SER UM CONTROLLER DE RECURSO
        // if( $tipo_arquivo == 'controller' &&  $controller_recurso != '--recurso' ){
        //     echo("\n\033[93m# Deseja que o controller já contenha todos os metodos http de recurso rest? [y/n]\033[0m\n\n");
        //     if((! in_array(readline('> '), ['n', 'no', 'nao'] ))){
        //         $controller_recurso = '--recurso';
        //     }
        // }

        if( empty($nome) ){
            echo("\n\033[93m# Qual o nome do(a) $tipo_arquivo?\033[0m\n\n");
            $nome = readline('> ');
        }

        $tipo_arquivo = ucfirst($tipo_arquivo);

        $caminho = match ($tipo_arquivo) {
            'Comando' =>  PASTA_RAIZ . 'app/Comandos/',
            'Controller' =>  PASTA_RAIZ . 'app/Controllers/',
            'Entidade' => PASTA_RAIZ . 'app/Entidades/',
            'Filtro' => PASTA_RAIZ . 'app/Filtros/',
            'Model' => PASTA_RAIZ . 'app/Models/',
            'Js' => PASTA_PUBLIC . '/js/',
            'Tabela' => PASTA_RAIZ . 'app/Database/tabelas/',
            default => die("\n\033[91m# Tipo de arquivo '$tipo_arquivo' não suportado.\033[0m")
        };
        $template = ($tipo_arquivo == 'Controller' && $flags == '--recurso') ? 'ControllerRecurso' : $tipo_arquivo;

        $template = require "templates/$template.php";

        if (str_contains($nome, '/')) {
            $caminho_full = dirname($caminho.$nome);
            $nome = basename($caminho.$nome);
            $namespace = '\\' . str_replace('/', '\\', ucwords(substr($caminho_full, strlen($caminho)), '/'));
            $caminho = $caminho_full . '/';
        }else{
            $namespace = '';
        }

        if($tipo_arquivo !== 'Tabela' && $tipo_arquivo !== 'Js'){
            $nome = ucfirst($nome);
        }

        if($tipo_arquivo == 'Model'){
            // sem sufixo model
            $tabela = str_ends_with($tabela = strtolower($nome), 'model') ? substr($tabela, 0, -5) : $tabela;
            // plural
            $tabela = str_ends_with($tabela, 's') ? $tabela : $tabela.'s';
            $arquivo = str_replace(['{nome}', '{namespace}', '{tabela}'], [$nome, $namespace, $tabela], $template);
        }else{
            $arquivo = str_replace(['{nome}', '{namespace}'], [$nome, $namespace], $template);
        }

        if($tipo_arquivo === 'Tabela'){
            $nome = date('Y-m-d-His_') . $nome;
        }

        if(!is_dir($caminho)){
            mkdir($caminho, recursive: true);
        }

        $extensao = strtolower($tipo_arquivo) == 'js' ? '.js' : '.php';

        if ( file_put_contents($caminho.$nome.$extensao, $arquivo) ) {
            $resposta = "\n\033[92m# $tipo_arquivo $nome criado com sucesso em: \n\033[0m$caminho$nome$extensao.\n\n";
        } else {
            $resposta = "\n\033[91m# Algo deu errado ao gerar o $tipo_arquivo.\n\033[0m";
        }

        echo($resposta);
    }


    /**
     * Habilita e configura o composer para o framework
     * @author Brunoggdev
    */
    public function habilitarComposer():void
    {
        if (file_put_contents('composer.json', require 'templates/composer.php') ) {
            exec('composer install');
            $resposta = "\n\033[92m# Composer habilitado com sucesso! Caso esteja usando git, lembre-se de adicionar a pasta /vendor/ no seu .gitignore.\n\033[0m";
        } else {
            $resposta = "\n\033[91m# Algo deu errado ao gerar o composer.json.\n\033[0m";
        }

        echo($resposta);
    }


    /**
     * Inicia um idle repl do PHP no terminal
     * @author Brunoggdev
    */
    public function fornalha():void
    {
        if (AMBIENTE !== 'desenvolvimento') {
            die("\n\033[91m# Fornalha não disponível fora do ambiente de desenvolvimento.\033[0m\n");
        }

        set_error_handler(function($errno, $errstr, $errfile, $errline){
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        // Habilitando sessão previamente por conta de buffer
        session_start();

        echo "\n\033[92m# Fornalha iniciada - Ambiente interativo do HefestosPHP.\033[0m";
        echo "\n\033[93m# Digite 'sair' ou pressione ctrl+c para sair.\033[0m";

        if (extension_loaded('readline')) {
            readline_completion_function('readline');
            readline_add_history("");
        }

        while (true) {

            echo "\n\n";

            try {
                echo "\033[91mFornalha > \033[0m\033[K";
                $_fornalha_entrada = readline("");

                if (in_array($_fornalha_entrada, ['sair', 'exit'])) {
                    break;
                }

                if (in_array($_fornalha_entrada, ['cls', 'clear', 'limpar'])) {
                    // Comando para limpar a tela no terminal
                    if (PHP_OS_FAMILY === 'Windows') {
                        pclose(popen("cls", "w"));
                    } else {
                        pclose(popen("clear", "w"));
                    }
                    continue;
                }

                echo PHP_EOL;

                if (extension_loaded('readline')) {
                    readline_add_history($_fornalha_entrada);
                }
                
                if (preg_match('/(echo|return|var_dump|print_r)/', $_fornalha_entrada) !== 1) {
                    $_fornalha_entrada = 'return ' . $_fornalha_entrada;
                }

                // SCRIPT PARA ADICIONAR ; SE NECESSÁRIO
                // if (!str_ends_with($_fornalha_entrada, '}') && !str_ends_with($_fornalha_entrada, ';')) {
                //     $_fornalha_entrada = $_fornalha_entrada.';';
                // }

                $_fornalha_saida = eval($_fornalha_entrada);
                isset($_fornalha_saida) && var_export($_fornalha_saida);
            } catch (\Throwable $th) {
                echo 
                "\033[91m -> Erro encontrado: \033[0m" . $th->getMessage() . "\n" . 
                "\033[91m -> Na linha: \033[0m" . $th->getLine() . "\n" . 
                "\033[91m -> Do arquivo: \033[0m" . $th->getFile();
            }
        }

    }



    /**
    * Executa todas as funcoes de teste e imprime seus resultados
    * @author Brunoggdev
    */
    public function testar(string $caminho):void
    {
        define('RODANDO_TESTES', true);

        require_once PASTA_RAIZ . 'system/Testes/auxiliares_de_testes.php';
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

        if (extension_loaded('pdo_sqlite')) {
            ob_start();
            $this->migrar(zerar:'zero');
            ob_clean();
        }
        
        $testador = new Testador(SuiteDeTestes::instancia());
        $testes_passaram = 0;
        $testes_falharam = 0;
        $cronometro = new Cronometro();
        $cronometro->iniciar();

        foreach ($testador->testes() as $teste) {

            try {
                $resultado = $testador->testar($teste['funcao']->bindTo($testador));
            } catch (\Throwable $th) {
                $resultado = false;
                $i = is_numeric($i = $th->getCode()) ? (int)$i : (str_starts_with((string)$i, 'HY') ? 2 : 0);      
                $trace = $th->getTrace()[$i];

                $erro = 
                    " \033[91m > Erro encontrado: \033[0m" . $th->getMessage() . "\n" . 
                    " \033[91m > Na linha: \033[0m" .  $trace['line'] . "\n" . 
                    " \033[91m > Do arquivo: \033[0m" . $trace['file'].' on line '.$trace['line'] . "\n";
            }

            if($resultado === false) {
                $status = "\n\033[41m FALHOU \033[0m";
                $testes_falharam++;
            }else{
                $status = "\033[42m PASSOU \033[0m";
                $testes_passaram++;
            }


            $this->imprimir("$status Testa $teste[descricao]", 0, false);

            if(isset($erro)){
                $this->imprimir($erro, 0, false);
                unset($erro);
            }
        }

        $cronometro->parar();

        $sqlite = PASTA_RAIZ . 'app/Database/sqlite/testes.sqlite';
        if (file_exists($sqlite)) {
            db()->fechar();
            unlink($sqlite);
        }

        echo "\n\n\n";
        $this->imprimir("\033[92mPassaram:\033[0m $testes_passaram.", 0);
        $this->imprimir("\033[91mFalharam:\033[0m $testes_falharam.", 0);
        $this->imprimir("\033[96mDuração:\033[0m {$cronometro->tempoCorrido()}s.");

    }


    /**
    * Executa as sql's de criação de tabelas
    * @author Brunoggdev
    */
    public function migrar(string $caminho = 'tabelas', string $zerar = ''):void
    {
        if ($caminho === 'zero') {
            $zerar = 'zero';
            $caminho = 'tabelas';
        }

        $caminho = 'app/Database/' . $caminho;
        $db = db();
        $tabelas_no_banco = $db->listarTabelas();

        // Se for um diretorio, busque todos os arquivos dentro
        if( is_dir($caminho) ){
            $arquivos_tabelas = array_merge(
                glob($caminho . '*.php'),
                glob($caminho . '**/*.php')
            );

            // Executa a sql retornada por cada arquivo
            foreach ($arquivos_tabelas as $arquivo_tabela) {
                /** @var \Hefestos\Database\Tabela */
                $tabela = require $arquivo_tabela;
                
                if (! str_starts_with($tabela, 'CREATE TABLE')){
                    throw new \Exception('Sql informada não é válida para esta operação:' . PHP_EOL . $tabela);
                }

                if($zerar === 'zero') {
                    $db->executar("DROP TABLE IF EXISTS $tabela->nome;");
                }

                // são 3 da manhã, dá um desconto
                // se não for pra zerar e a tabela já existir no banco
                if ($zerar !== 'zero' && in_array($tabela->nome, $tabelas_no_banco)) {
                    $db->tabela($tabela->nome);

                    // confira se tem atualizações de forma incremental por performance e, se sim, atualize a tabela
                    if (($colunas = $db->listarColunas()) && ($fks = $db->listarForeignKeys())) {
                        /** @disregard P1013 intelephense error*/
                        $alter_table_sql = (fn() => $this->atualizarSchema($colunas, $fks))->call($tabela);
                        $db->executar($alter_table_sql);
                    }

                } else {
                    $db->executar($tabela);
                }

            }

            echo "\n\033[92m# Tabelas criadas/atualizadas". ($zerar === 'zero' ? ' do zero ' : ' ') ."com sucesso!\033[0m";

        }else{
            // se não, busque apenas o arquivo informado
            try{
                /** @var \Hefestos\Database\Tabela */
                $tabela = is_file($caminho) ? require $caminho : tabela(explode('/', $caminho)[2]);
            } catch (\ErrorException){
                $this->imprimir('Arquivo não encontrado.');
                exit;
            }

            if (! str_starts_with($tabela, 'CREATE TABLE')) {
                throw new \Exception('Sql informada não é válida para esta operaçã:' . PHP_EOL . $tabela);
            }

            if($zerar === 'zero') {
                $db->executar("DROP TABLE IF EXISTS $tabela->nome;");
            }

            $db->executar($tabela);

            echo "\n\033[92m# Tabela '$tabela->nome' criada/atualizada". ($zerar === 'zero' ? ' do zero ' : ' ') ."com sucesso!\033[0m";

        }
    }



    /**
     * Permite que o usuario cadastre os próprios comandos para que sejam executados pela forja
     */
    public function comandosDoUsuario(array $argumentos):void
    {
        $comando = ucfirst(array_shift($argumentos));
        $caminho = pasta_app("Comandos/");

        if (!is_file("$caminho/$comando.php")) {
            echo "\n\033[91m# Comando '$comando' não encontrado em '$caminho'.\033[0m\n\n";
            exit;
        }

        $comando = "App\\Comandos\\$comando";

        (new $comando)->executar(...$argumentos);
    }



    /**
    * Imprime a resposta desejada no terminal
    * @author Brunoggdev
    */
    private function imprimir(string $resposta, int $eol = 2, $com_hashtag = true)
    {
        echo "\n" . ($com_hashtag ? "# " : '') . $resposta . str_repeat(PHP_EOL, $eol);
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
        $this->imprimir('|  inciar  |           ----------------------            | php forja iniciar                          |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  criar   | [controller, model, filtro, tabela] + nome  | php forja criar controller NotasController |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  testar  | pasta/arquivo especifico (opcional)         | php forja testar (HefestosPHP)             |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------', 0);
        $this->imprimir('|  migrar  | pasta/arquivo especifico (opcional)         | php forja migrar (usuarios.php)            |', 0);
        $this->imprimir('-------------------------------------------------------------------------------------------------------');
    }
}
