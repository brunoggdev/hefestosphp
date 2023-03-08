<?php

namespace System\Core;

/**
* Controla todo o sistema de roteamento da aplicação
* @author Brunoggdev
*/
class Roteador {

    protected $rotas = [];


    /**
    * Adiciona uma rota get no array de rotas
    * @author Brunoggdev
    */
    public function get($uri, $acao)
    {
        $this->adicionar('GET', $uri, $acao);
        return $this;
    }
    
    
    
    /**
    * Adiciona uma rota post no array de rotas
    * @author Brunoggdev
    */
    public function post($uri, $acao)
    {
        $this->adicionar('POST', $uri, $acao);
        return $this;
    }
    
    
    
    /**
    * Adiciona uma rota put no array de rotas
    * @author Brunoggdev
    */
    public function put($uri, $acao)
    {
        $this->adicionar('PUT', $uri, $acao);
        return $this;
    }



    /**
    * Adiciona uma rota patch no array de rotas
    * @author Brunoggdev
    */
    public function patch($uri, $acao)
    {
        $this->adicionar('PATCH', $uri, $acao);
        return $this;
    }



    /**
    * Adiciona uma rota delete no array de rotas
    * @author Brunoggdev
    */
    public function delete($uri, $acao)
    {
        $this->adicionar('DELETE', $uri, $acao);
        return $this;
    }


    /**
    * Adiciona uma nova rota no array de rotas
    * @author Brunoggdev
    */
    protected function adicionar($verbo_http, $uri, $acao):void
    {
        $acao = explode('::', $acao);

        $this->rotas[] = [
            'uri' => strip_tags($uri),
            'controller' => $acao[0],
            'metodo' => $acao[1],
            'verbo_http' => $verbo_http,
            'filtro' => null
        ];
    }


    /**
    * Adiciona o filtro especificado na rota em que foi chamado;
    * @author Brunoggdev
    */
    public function filtro($filtro):void
    {
        $this->rotas[array_key_last($this->rotas)] = $filtro;
        dd($this->rotas);
    }


    /**
    * Tenta mapear a uri requisitada com uma das rotas configuradas
    * @author Brunoggdev
    */
    public function mapear($uri, $verbo_http):string
    {
        foreach ($this->rotas as $rota) {
            if( $rota['uri'] !== $uri || $rota['verbo_http'] !== strtoupper($verbo_http) ){
                return abortar();
            }

            // Adicionando namespace padrão dos controllers para autoload
            $controller = "App\Controllers\\$rota[controller]";
            
            // Chamando o controller e seu método
            return call_user_func( [new $controller(), $rota['metodo'] ] );
        }
    }
}