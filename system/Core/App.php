<?php

namespace Hefestos\Core;

use Throwable;

final class App
{
    /**
     * Utiliza o roteador para buscar a devida resposta à requisicao.
     * @author Brunoggdev
    */
    public static function processarRequisicao():string
    {
        require PASTA_RAIZ . '/app/Config/rotas.php';
    
        [$uri, $metodo_http] = static::processarURL();
        
        return $rotas->mapear($uri, $metodo_http);   
    }

    private function processarURL(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $metodo_http = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

        return [$uri, $metodo_http];
    }

    public function encerrar(string $resposta): void
    {
        echo $resposta;
        exit;
    }

    private function lidarComErro(Throwable $erro): void
    {
        $codigo_http = 500;

        $retorno = ENVIROMENT === 'desenvolvimento' // se for desenvolvimento
        ? view('debug', ['erro' => $erro])          // carregue view de debug com erros
        : view($codigo_http);                       // senão uma view genérica
        
        abortar($codigo_http, $retorno);
    }
}
