<?php

namespace Hefestos\Rotas;

class Requisicao
{
    protected static ?self $instancia = null;


    /**
     * Retorna parametros enviados por get já higienizados.
     * @author Brunoggdev
     */
    public static function dadosGet(null|string|array $index = null, $higienizar = true): mixed
    {
        $retorno = match (gettype($index)) {
            'string' => $_GET[$index] ?? null,
            'array' => array_intersect_key($_GET, array_flip($index)),
            default => $_GET
        };

        if (isset($retorno['_method'])) {
            unset($retorno['_method']);
        }

        return $higienizar ? higienizar($retorno) : $retorno;
    }



    /**
     * Retorna parametros enviados por post já higienizados.
     * @param array|string|null $index Index para resgatar do $_POST.
     * @author Brunoggdev
     */
    public static function dadosPost(null|string|array $index = null, $higienizar = true): mixed
    {
        $retorno = match (gettype($index)) {
            'string' => $_POST[$index] ?? null,
            'array' => array_intersect_key($_POST, array_flip($index)),
            default => $_POST
        };

        if (isset($retorno['_method'])) {
            unset($retorno['_method']);
        }

        return $higienizar ? higienizar($retorno) : $retorno;
    }



    /**
     * Verifica se a requisição atual foi feita com AJAX 
     * (baseado nos cabeçalhso X-Requested-With e Sec-Fetch-Mode)
     * `ATENÇÃO: CABEÇALHOS NEM SEMPRE PODEM SER CONFIADOS E, PORTANTO, O MESMO VALE PARA ESSA FUNÇÃO`
     * @author Brunoggdev
     */
    public static function ajax()
    {
        $requested_with_XMLHttpRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        $sec_fetch_mode = isset($_SERVER['HTTP_SEC_FETCH_MODE']) ? $_SERVER['HTTP_SEC_FETCH_MODE'] : '';

        return $requested_with_XMLHttpRequest || $sec_fetch_mode !== 'navigate';
    }



    /**
     * Acessa a query string da URL e retorna a chave desejada 
     * ou inteira se uma não for informada (ou null caso não existam);
     * @author Brunoggdev
     */
    public static function query_string(?string $chave = null, $higienizar = false): ?string
    {
        $retorno = $chave
            ? $_GET[$chave] ?? null
            : $_SERVER['QUERY_STRING'] ?? null;

        return $higienizar ? higienizar($retorno) : $retorno;
    }


    
    /**
     * Retorna a instancia da classe contendo informações sobre a requisição atual (singleton).
     * @author Brunoggdev
     */
    public static function instancia(): self
    {
        if (is_null(self::$instancia)) {
            self::$instancia = new self();
        }

        return self::$instancia;
    }
}
