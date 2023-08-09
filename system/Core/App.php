<?php

namespace Hefestos\Core;

final class App
{
    /**
     * Utiliza o roteador para buscar a devida resposta à requisicao.
     * @author Brunoggdev
    */
    public static function processarRequisicao():string
    {
        try {
            require PASTA_RAIZ . '/app/Config/rotas.php';
        
            $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
            $metodoHttp = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
            
            // Enviando resposta da requisicao e finalizando o app
            echo $rotas->mapear($uri, $metodoHttp);
            exit;

        } catch (\Throwable $erro) {
            ob_clean(); // Limpa o buffer de saída
            
            $retorno = ENVIROMENT === 'producao' ? abortar(500)
            die( view('debug', ['erro' => $erro]) );
        }        
    }
}
