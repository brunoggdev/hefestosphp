<?php

namespace System;

/**
* Controla todo o sistema de roteamento da aplicação
* @author Brunoggdev
*/
class Roteador {

    protected $rotas = [];


    /**
    * Adiciona uma nova rota no array de rotas
    * @author Brunoggdev
    */
    protected function adicionar($requisicao, $uri, $acao):void
    {
        $acao = explode(':', $acao);

        $this->rotas[] = [
            'uri' => $uri,
            'controller' => $acao[0],
            'metodo' => $acao[1],
            'requisicao' => $requisicao
        ];
    }


    /**
    * Adiciona uma rota get no array de rotas
    * @author Brunoggdev
    */
    public function get($uri, $acao)
    {
       $this->adicionar('GET', $uri, $acao);
    }
    
    
    
    /**
    * Adiciona uma rota post no array de rotas
    * @author Brunoggdev
    */
    public function post($uri, $acao)
    {
        $this->adicionar('POST', $uri, $acao);
    }
    
    
    
    /**
    * Adiciona uma rota put no array de rotas
    * @author Brunoggdev
    */
    public function put($uri, $acao)
    {
        $this->adicionar('PUT', $uri, $acao);
    }



    /**
    * Adiciona uma rota patch no array de rotas
    * @author Brunoggdev
    */
    public function patch($uri, $acao)
    {
        $this->adicionar('PATCH', $uri, $acao);
    }



    /**
    * Adiciona uma rota delete no array de rotas
    * @author Brunoggdev
    */
    public function delete($uri, $acao)
    {
        $this->adicionar('DELETE', $uri, $acao);
    }



    /**
    * Tenta mapear a uri requisitada com uma das rotas configuradas
    * @author Brunoggdev
    */
    public function mapear($uri, $requisicao):string
    {
        foreach ($this->rotas as $rota) {

            if( $rota['uri'] !== $uri || $rota['requisicao'] !== strtoupper($requisicao) ){
                abortar();
            }

                            
            $controller = "App\Controllers\\$rota[controller]";
                
            return call_user_func( [new $controller(), $rota['metodo'] ] );
        }
    }
}