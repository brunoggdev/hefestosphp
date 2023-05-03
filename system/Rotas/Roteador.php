<?php

namespace System\Rotas;

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

        if ( $acao  instanceof Closure) {
            $rota['callback'] = $acao;
        }else{

            if(! is_array($acao) ){
                $acao = explode('::', $acao);
            }
            
            // Adicionando namespace padrão caso não haja nenhum
            $rota['controller'] = str_contains($acao[0], '\\') ? $acao[0] : "$this->namespacePadrao\\$acao[0]";
            $rota['metodo'] = $acao[1];

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

            if( 
                // checando se o verbo http está correto
                $rota['verbo_http'] === strtoupper($verbo_http) 
                && 
                // mapeando com regex para identificar coringas e separar em params
                preg_match('#^'.$rota['uri'].'$#', $uri, $params ) 
            ){
                (new Filtros)->filtrar($rota['filtro']);
                
                return $this->resposta($rota, $params);
            }
        }
 
        abortar();
    }


    /**
    * Devolve a resposta da rota
    * @author Brunoggdev
    */
    public function resposta($rota, $params):string
    {
        // Verificando se a resposta para a rota é uma callback ou metodo de controller
        if( $rota['callback'] ?? false){
            // chamando a callback e passando params caso existam
            return call_user_func($rota['callback'], ...array_slice($params, 1) );
        }else{
            // Chamando o controller e seu método, passando params caso existam
            return call_user_func( [new $rota['controller'](), $rota['metodo']], ...array_slice($params, 1) );
        }
    }
}