<?php

namespace Hefestos\Rotas;

class Redirecionar{

    /**
    * Redireciona para a url informada com o código informado (302 padrão)
    * @author Brunoggdev
    */
    public function para(string $url, int $codigo = 302):self
    {
        if(! $url) {
            throw new \Exception('Nenhuma URL recebida para redirecionamento.');
        }

        if (!str_starts_with($url, 'http') && !str_starts_with($url, 'www')) {
            $url = url_base($url);
        }
        
        http_response_code($codigo);
        header("Location: $url");

        return $this;
    }


    /**
    * Adiciona uma mensagem flash para o redirecionamento
    * @author Brunoggdev
    */
    public function com(string $chave, mixed $valor):self
    {
        sessao()->flash($chave, $valor);
        return $this;
    }


    /**
    * Define a url de retorno como a url que chamou o endpoint atual
    * @author Brunoggdev
    */
    public function deVolta():self
    {
        $this->para(requisicao()->referer());
        return $this;
    }
}
