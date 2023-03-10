<?php

namespace System\Core\Classes;

class Redirecionar{

    /**
    * Redireciona para a url informada com o código informado (302 padrão)
    * @author Brunoggdev
    */
    public function para(string $url, int $codigo = 302):void
    {
        if(! $url) {
            throw new \Exception('Nenhuma URL recebida para redirecionamento.');
        }
        
        http_response_code($codigo);
        header("Location: $url");
    }


    /**
    * Adiciona uma mensagem flash para o redirecionamento
    * @author Brunoggdev
    */
    public function com(string $chave, mixed $valor):void
    {
        sessao()->flash($chave, $valor);
    }
}