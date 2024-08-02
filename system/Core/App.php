<?php

namespace Hefestos\Core;

use Hefestos\Rotas\Redirecionar;
use Hefestos\Rotas\Roteador;

final class App
{
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

            $requisicao = $app->analisarRequisicao();
            $acao = Roteador::instancia()->mapear(...$requisicao);
            $resposta = $app->processarResposta(...$acao);

            $app->encerrar($resposta);
        } catch (\Throwable $erro) {
            $app->lidarComErro($erro);
        }
    }


    private function lidarComErro(\Throwable $erro): void
    {
        $codigo_http = 500;

        $retorno = AMBIENTE === 'desenvolvimento' // se for desenvolvimento
            ? view('debug', ['erro' => $erro])    // carregue view de debug com erros
            : view($codigo_http);                 // e, se não, uma view de erro genérica


        if (AMBIENTE != 'desenvolvimento') {
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


        abortar($codigo_http, $retorno);
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

        if (!is_scalar($resposta)) {

            throw new \Exception("O tipo de resposta retornada não pode ser enviado. Tipo '"
                . ucfirst(get_debug_type($resposta)) . "' retornado. Normalmente você deve retornar uma string, int ou um Redirecionar.");


            // $reflection = new \ReflectionFunction($acao);

            // $funcao = $reflection->getName();
            // $arquivo = basename($reflection->getFileName());
            // $linha = $reflection->getStartLine();

            // throw new \Exception("O tipo de resposta retornada não pode ser exibido. Normalmente você deve retornar uma string, int ou um redirecionar(). Tipo '"
            // . ucfirst(get_debug_type($resposta)) . "' recebido de '$funcao', na linha '$linha' do arquivo '$arquivo'.");

        }

        return $resposta;
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
     * Envia a resposta (string) pro cliente e encerra o app;
     * @author Brunoggdev
     */
    private function encerrar(string $resposta): void
    {
        echo $resposta;
        exit;
    }
}
