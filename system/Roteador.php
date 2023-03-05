<?php

namespace System;


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
        $this->rotas[] = [
            'uri' => $uri,
            'controller' => $acao[0],
            'metodo' => $acao[1],
            'method' => 'GET'
        ];
    }
    
    
    
    /**
    * Adiciona uma rota post no array de rotas
    * @author Brunoggdev
    */
    public function post($uri, $acao)
    {
        $this->rotas[] = [
            'uri' => $uri,
            'controller' => $acao[0],
            'metodo' => $acao[1],
            'method' => 'POST'
        ];
    }
    
    
    
    /**
    * Adiciona uma rota put no array de rotas
    * @author Brunoggdev
    */
    public function put($uri, $acao)
    {
        $this->rotas[] = [
            'uri' => $uri,
            'controller' => $acao[0],
            'metodo' => $acao[1],
            'method' => 'PUT'
        ];
    }



    /**
    * Adiciona uma rota patch no array de rotas
    * @author Brunoggdev
    */
    public function patch($uri, $acao)
    {
        $this->rotas[] = [
            'uri' => $uri,
            'controller' => $acao[0],
            'metodo' => $acao[1],
            'method' => 'PATCH'
        ];
    }



    /**
    * Adiciona uma rota delete no array de rotas
    * @author Brunoggdev
    */
    public function delete($uri, $acao)
    {
        $this->rotas[] = [
            'uri' => $uri,
            'controller' => $acao[0],
            'metodo' => $acao[1],
            'method' => 'DELETE'
        ];
    }



    /**
    * Tenta mapear a uri requisitada com uma das rotas configuradas
    * @author Brunoggdev
    */
    public function mapear($uri, $metodoRequisicao)
    {
        foreach ($this->rotas as $rota) {

            if( $rota['uri'] === $uri){
                



            }

        }
    }
}