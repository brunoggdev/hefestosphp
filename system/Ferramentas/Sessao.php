<?php

namespace Hefestos\Ferramentas;

class Sessao
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

        // Utilizado para marcar itens como flash
        $_SESSION['__flash'] ??= [];
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
    public function pegar(string $chave, $higienizar = true):mixed
    {
        $retorno = $_SESSION[$chave] ?? null;

        // Verifica se a chave foi marcada como flash
        if( in_array($chave, $_SESSION['__flash']) ){
            // removendo a chave da sessão e sua marca como flash
            unset($_SESSION[$chave]);
            unset($_SESSION['__flash'][array_search($chave, $_SESSION['__flash'])]);
        }

        return $higienizar ? higienizar($retorno) : $retorno;
    }


    /**
    * Verifica se o a chave desejada existe na sessão
    * @author Brunoggdev
    */
    public function tem(string $chave):bool
    {
        return isset($_SESSION[$chave]);
    }


    /**
    * Adiciona um novo campo flash na sessão
    * @author Brunoggdev
    */
    public function flash(string $chave, mixed $valor):void
    {
        // Adiciona item na sessão normalmente
        $this->guardar($chave, $valor);

        // Marca que esse item é do tipo flash
        $_SESSION['__flash'][] = $chave;
    }


    /**
    * Limpa da session a chave especificada ou conjunto 
    * de chaves caso seja informado um array de chaves
    * @author Brunoggdev
    */
    public function limpar(string|array $chave):void
    {
        if (is_array($chave)) {
            foreach ($chave as $item) {
                unset($_SESSION[$item]);
            }

            return;
        }

        unset($_SESSION[$chave]);
    }


    /**
    * Destroi por completo a sessao
    * @author Brunoggdev
    */
    public function destruir():void
    {
        session_destroy();
    }
}
