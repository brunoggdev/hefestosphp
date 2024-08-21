<?php

namespace Hefestos\Core;

use Hefestos\Ferramentas\Sessao;
use Hefestos\Rotas\Redirecionar;
use Hefestos\Rotas\Roteador;
use Throwable;

final class App
{

    private static array $respostas = [];
    private static array $excecoes = [];

    /**
     * Utiliza o roteador para buscar a devida resposta à requisicao.
     * @author Brunoggdev
     */
    public static function processarRequisicao(): void
    {
        try {
            $app = new self();

            if (MANUTENCAO) {
                $app->encerrar(view('manutencao'));
            }

            Sessao::iniciar();

            $requisicao = $app->analisarRequisicao();
            $acao = Roteador::instancia()->mapear(...$requisicao);
            $resposta = $app->processarResposta(...$acao);

            $app->encerrar($resposta);
        } catch (Throwable $erro) {
            $app->lidarComErro($erro);
        }
    }


    


    /**
     * Analisa a requisicao recebida para retornar a url requisitada e
     * o método http da requisicao (GET, POST etc).
     * @author Brunoggdev
     */
    private function analisarRequisicao(): array
    {
        $url = parse_url($_SERVER['REQUEST_URI'])['path'];
        $metodo_http = strip_tags($_POST['_method'] ?? $_SERVER['REQUEST_METHOD']);

        if (isset($_POST['_method'])) {
            unset($_POST['_method']);
        }

        return [$url, $metodo_http];
    }

    


    /**
     * Permite definir funções para tratar exceções específicas no último nível de execução
     * @param string $nome_excecao O 'nome qualificado' da exceção a ser tratada (Ex: Exception::class)
     * @param callable $tratador Função a ser invocada se a exceção informada não for tratada em um try/catch prévio (A Exceção será passado como argumento).
     */
    public static function tratarExcecao(string $nome_excecao, callable $tratador):void
    {
        self::$excecoes[$nome_excecao] = $tratador;
    }



    /**
     * Permite definir funções para tratar respostas específicas no último nível de execução
     * @param string $nome_resposta O 'nome qualificado' da classe de a ser tratada (Ex: MinhaResposta::class)
     * @param callable $tratador Função a ser invocada se a exceção informada não for tratada em um try/catch prévio (A Exceção será passado como argumento).
     */
    public static function tratarResposta(string $nome_resposta, callable $tratador):void
    {
        self::$respostas[$nome_resposta] = $tratador;
    }



    private function lidarComErro(\Throwable $erro): void
    {
        if (ob_get_status()) {
            ob_end_clean();
        }
        
        if (AMBIENTE != 'desenvolvimento') {
            $this->logDeErro($erro);
        }
        
        foreach (static::$excecoes as $excecao => $tratador) {
            if ($erro instanceof $excecao) {
                $tratador($erro);
                return;
            }
        }

        $codigo_http = 500;

        $retorno = match (true) {
            requisicao()->ajax() => json(
                ['status' => $codigo_http,'message' => $erro->getMessage()]
                + (AMBIENTE === 'desenvolvimento' ? ['trace' => $erro->getTrace()] : [])
            ),
            AMBIENTE === 'desenvolvimento' => view('debug', ['erro' => $erro]),
            AMBIENTE === 'producao' => view($codigo_http),
            default => null
        };

        
        abortar($codigo_http, $retorno);
    }



    /**
     * Gera log baseado em uma exeção
     */
    private function logDeErro(Throwable $erro):void
    {
        $log_de_erro = 'Erro encontrado: ' . $erro->getMessage();

        foreach ($erro->getTrace() as $key => $traco) {
            $log_de_erro .= "\n" . '               > NA LINHA: ' . $traco['line'] ??= 'Não especificada';
            $log_de_erro .= "\n" . '               > DO ARQUIVO: ' . $traco['file'] ??= 'Não especificado';
            $log_de_erro .= "\n" . '               ' . str_repeat('-', 80);

            if($key == array_key_last($erro->getTrace())){
                $log_de_erro .= "\n";
            }
        }

        gerar_log($log_de_erro);
    }



    /**
     * Recebe e executa a acao cadastrada para a rota utilizando os parametros recebidos
     * @author Brunoggdev
     */
    private function processarResposta(callable $acao, array $params): string
    {
        $resposta = $acao(...$params);

        if ($resposta instanceof Redirecionar) {
            exit;
        }
        
        // Atualizamos a resposta baseado no tratador definido caso haja um
        foreach (static::$respostas as $resposta_custom => $tratador) {
            if ($resposta instanceof $resposta_custom) {
                $resposta = $tratador($resposta);
                break;
            }
        }

        if (!is_scalar($resposta)) {
            throw new \Exception("O tipo de resposta retornada não pode ser enviado. Tipo '"
                . ucfirst(get_debug_type($resposta)) . "' encontrado. Normalmente você deve retornar uma string, int ou um Redirecionar.");
        }

        return $resposta;
    }



    /**
     * Envia a resposta (string) pro cliente e encerra o app;
     * @author Brunoggdev
     */
    private function encerrar(string $resposta): void
    {
        Sessao::limparSessoesFlash();

        echo $resposta;

        exit;
    }
}
