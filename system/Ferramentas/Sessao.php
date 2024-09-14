<?php

namespace Hefestos\Ferramentas;

class Sessao
{

    /**
     * Inicia a sessão e a chave flash caso não estejam ativas
     * @author Brunoggdev
     */
    public static function iniciar()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Utilizado para marcar itens como flash
        $_SESSION['__flash'] ??= [];
    }


    /**
     * Guarda um item na sessão
     * @author Brunoggdev
     */
    public static function guardar(string $chave, mixed $valor): void
    {
        $_SESSION[$chave] = $valor;
    }


    /**
     * Pega um item da sessão, retorna null caso não exista
     * @author Brunoggdev
     */
    public static function pegar(string $chave, $higienizar = true): mixed
    {
        $retorno = dot_notation($chave, $_SESSION);

        return $higienizar ? higienizar($retorno) : $retorno;
    }


    /**
     * Verifica se o a chave desejada existe na sessão
     * @author Brunoggdev
     */
    public static function tem(string $chave): bool
    {
        return isset($_SESSION[$chave]);
    }


    /**
     * Adiciona um novo campo flash na sessão
     * @author Brunoggdev
     */
    public static function flash(string $chave, mixed $valor): void
    {
        // Adiciona item na sessão normalmente
        self::guardar($chave, $valor);

        // Marca que esse item é do tipo flash
        $_SESSION['__flash'][] = $chave;
    }


    /**
     * Limpa da session a chave especificada ou conjunto 
     * de chaves caso seja informado um array de chaves
     * @author Brunoggdev
     */
    public static function limpar(string|array $chave): void
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
     * Limpa as chaves de sessão que foram marcadas como flash
     */
    public static function limparSessoesFlash()
    {
        foreach ($_SESSION['__flash'] as $chave) {
            // removendo a chave da sessão e sua marca como flash
            unset($_SESSION[$chave]);
            unset($_SESSION['__flash'][array_search($chave, $_SESSION['__flash'])]);
        }
    }


    /**
     * Update the current session id with a newly generated one
     * @author Brunoggdev
     */
    public static function regenerarId(): void
    {
        session_regenerate_id(true);
    }


    /**
     * Destroi por completo a sessao
     * @author Brunoggdev
     */
    public static function destruir(): void
    {
        session_destroy();
    }
}
