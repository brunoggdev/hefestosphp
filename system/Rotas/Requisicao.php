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

        return $higienizar ? higienizar($retorno) : $retorno;
    }



    /**
     * Retorna parametros enviados por post já higienizados.
     * @param array|string|null $index Index para resgatar do $_POST.
     * @author Brunoggdev
     */
    public static function dadosPost(null|string|array $index = null, $higienizar = true): mixed
    {
        // Decidindo se a fonte de dados é campos de formulário POST padrão ou um JSON
        $fonte_de_dados = str_contains($_SERVER['CONTENT_TYPE'] ?? '', "application/json")
            ? json_decode(file_get_contents('php://input'), true) ?? []
            : $_POST;

        $retorno = match (gettype($index)) {
            'string' => $fonte_de_dados[$index] ?? null,
            'array' => array_intersect_key($fonte_de_dados, array_flip($index)),
            default => $fonte_de_dados
        };

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
     * Retorna a query string da URL ou null caso não exista.
     * @author Brunoggdev
     */
    public static function query_string(): ?string
    {
        return $_SERVER['QUERY_STRING'] ?? null;
    }


    /**
     * Retorna o http referer (útil para saber de onde veio o request)
     * @author Brunoggdev
     */
    public static function referer(): ?string
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }


    /**
     * Verifica (sem acessar) se existem quaisquer dados na requisição post ou, opcionalmente, em uma chave especifica
     */
    public static function temPost(?string $chave = null): bool
    {
        return $chave ? isset($_POST[$chave]) : !empty($_POST);
    }


    /**
     * Verifica (sem acessar) se existem quaisquer dados na requisição get ou, opcionalmente, em uma chave especifica
     */
    public static function temGet(?string $chave = null): bool
    {
        return $chave ? isset($_GET[$chave]) : !empty($_GET);
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
