<?php

namespace Hefestos\Rotas;

use Closure;

/**
* Controla todo o sistema de roteamento da aplicação
* @author Brunoggdev
*/
class Roteador {

    protected $rotas = [];
    protected $namespacePadrao;

    /**
    * Adiciona uma rota get no array de rotas
    * @author Brunoggdev
    */
    public function get(string $uri, string|array|callable $acao)
    {
        $this->adicionar('GET', $uri, $acao);
        return $this;
    }
    
    
    
    /**
    * Adiciona uma rota post no array de rotas
    * @author Brunoggdev
    */
    public function post(string $uri, string|array|callable $acao)
    {
        $this->adicionar('POST', $uri, $acao);
        return $this;
    }
    
    
    
    /**
    * Adiciona uma rota put no array de rotas
    * @author Brunoggdev
    */
    public function put(string $uri, string|array|callable $acao)
    {
        $this->adicionar('PUT', $uri, $acao);
        return $this;
    }



    /**
    * Adiciona uma rota patch no array de rotas
    * @author Brunoggdev
    */
    public function patch(string $uri, string|array|callable $acao)
    {
        $this->adicionar('PATCH', $uri, $acao);
        return $this;
    }



    /**
    * Adiciona uma rota delete no array de rotas
    * @author Brunoggdev
    */
    public function delete(string $uri, string|array|callable $acao)
    {
        $this->adicionar('DELETE', $uri, $acao);
        return $this;
    }


    /**
    * Adiciona uma nova rota no array de rotas
    * @author Brunoggdev
    */
    protected function adicionar(string $verbo_http, string $uri, string|array|callable $acao):void
    {
        $rota = [
            'uri' => str_replace( '{param}', '(.*)', strip_tags($uri) ),
            'verbo_http' => $verbo_http,
            'filtro' => ''
        ];

        if ($acao  instanceof Closure) {
            $rota['handler'] = $acao;
        }else{

            if (is_string($acao)) {
                $acao = explode('::', $acao);
            }
            
            // Adicionando namespace padrão caso não haja nenhum
            $rota['handler'][0] = str_contains($acao[0], '\\') ? $acao[0] : "$this->namespacePadrao\\$acao[0]";
            $rota['handler'][1] = $acao[1];
        }

        $this->rotas[] = $rota;
    }


    /**
    * Configura o namespare padrão para os metodos do controller
    * @author Brunoggdev
    */
    public function namespacePadrao(string $namespace):void
    {
        $this->namespacePadrao = $namespace;
    }


    /**
    * Adiciona o filtro especificado na rota em que foi chamado;
    * @author Brunoggdev
    */
    public function filtro(string $filtro):void
    {
        $this->rotas[array_key_last($this->rotas)]['filtro'] = $filtro;
    }


    /**
    * Tenta mapear a uri requisitada com uma das rotas configuradas
    * @author Brunoggdev
    */
    public function mapear(string $uri, string $verbo_http):string
    {
        foreach ($this->rotas as $rota) {
            $verbo_http_corresponde = $rota['verbo_http'] === strtoupper($verbo_http);
            $uri_corresponde = preg_match('#^'.$rota['uri'].'$#', $uri, $params);
            
            if ($verbo_http_corresponde && $uri_corresponde) {
                (new Filtros)->filtrar($rota['filtro']);
                return $this->resposta($rota, $params);
            }
        }
 
        abortar(404);
    }


    /**
    * Devolve a resposta da rota
    * @author Brunoggdev
    */
    public function resposta($rota, $params):string
    {
        $retorno = call_user_func($rota['handler'], ...array_slice($params, 1));
        
        if($retorno instanceof Redirecionar){
            exit;
        }

        return $retorno;
    }
}