<?php

namespace Hefestos\Core;

use Hefestos\Rotas\Redirecionar;

final class App
{
    /**
     * Utiliza o roteador para buscar a devida resposta à requisicao.
     * @author Brunoggdev
    */
    public static function processarRequisicao():void
    {
        try {
            $app = new self();

            if (MANUTENCAO) {
                $app->encerrar( view('manutencao') );
            }

            require PASTA_RAIZ . '/app/Config/rotas.php';
            
            $requisicao = $app->analisarRequisicao();
            $acao = $rotas->mapear(...$requisicao);
            $resposta = $app->processarResposta(...$acao);

            $app->encerrar($resposta);

        } catch (\Throwable $erro) {
            $app->lidarComErro($erro);
        }
    }


    private function lidarComErro(\Throwable $erro): void
    {
        $codigo_http = 500;

        $retorno = ENVIROMENT === 'desenvolvimento' // se for desenvolvimento
        ? view('debug', ['erro' => $erro])          // carregue view de debug com erros
        : view($codigo_http);                       // e, se não, uma view genérica
        
        abortar($codigo_http, $retorno);
    }


    /**
     * Recebe e executa a acao cadastrada para a rota utilizando os parametros recebidos
     * @author Brunoggdev
    */
    private function processarResposta(callable $acao, array $params):string
    {
        $resposta = $acao(...$params);
        
        if ($resposta instanceof Redirecionar) {
            exit;
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
