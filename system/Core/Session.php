<?php

namespace System\Core;

class Session
{


    /**
    * Inicia a sessão e a chave flash caso não estejam ativas
    * @author Brunoggdev
    */
    public function __construct()
    {
        if( session_status() !== PHP_SESSION_ACTIVE ){
            session_start();
        }

        $_SESSION['flash'] ??= [];
    }


    /**
    * Guarda um item na sessão
    * @author Brunoggdev
    */
    public function guardar(string $chave, mixed $valor):void
    {
        $_SESSION[$chave] = $valor;
    }
    
    
    /**
    * Pega um item da sessão, retorna null caso não exista
    * @author Brunoggdev
    */
    public function pegar(string $chave):mixed
    {
        $retorno = $_SESSION[$chave] ?? null;

        if( in_array($chave, $_SESSION['flash']) ){
            // removendo a chave da sessão pois é flash
            unset($_SESSION[$chave]);
            // removendo a chave do array flash 
            unset($_SESSION['flash'][array_search($chave, $_SESSION['flash'])]);
        }

        return $retorno;
    }


    /**
    * Verifica se o a chave desejada existe na sessão
    * @author Brunoggdev
    */
    public function tem(string $chave):bool
    {
        return $_SESSION[$chave]??false ? true : false;
    }


    /**
    * Adiciona um novo campo flash na sessão
    * @author Brunoggdev
    */
    public function flash(string $chave, $valor):void
    {
        $this->guardar($chave, $valor);
        $_SESSION['flash'][] = $chave;
    }
}
