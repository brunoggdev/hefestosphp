<?php

namespace Hefestos\Rotas;

/**
 * Controla todo o sistema de roteamento da aplicação
 * @author Brunoggdev
 */
class Rota
{

    protected static ?self $instancia = null;
    protected static array $rotas = [];
    protected static string $namespace_padrao = '\App\Controllers';

    /**
     * Retorna a instancia do roteador do app (singleton).
     * @author Brunoggdev
     */
    public static function instancia(): self
    {
        if (is_null(self::$instancia)) {
            self::$instancia = new self();
        }

        return self::$instancia;
    }


    /**
     * Adiciona uma rota get no array de rotas
     * @author Brunoggdev
     */
    public static function get(string $uri, string|array|callable $acao)
    {
        return self::instancia()->adicionar('GET', $uri, $acao);
    }



    /**
     * Adiciona uma rota post no array de rotas
     * @author Brunoggdev
     */
    public static function post(string $uri, string|array|callable $acao)
    {
        return self::instancia()->adicionar('POST', $uri, $acao);
    }



    /**
     * Adiciona uma rota put no array de rotas
     * @author Brunoggdev
     */
    public static function put(string $uri, string|array|callable $acao)
    {
        return self::instancia()->adicionar('PUT', $uri, $acao);
    }



    /**
     * Adiciona uma rota patch no array de rotas
     * @author Brunoggdev
     */
    public static function patch(string $uri, string|array|callable $acao)
    {
        return self::instancia()->adicionar('PATCH', $uri, $acao);
    }



    /**
     * Adiciona uma rota delete no array de rotas
     * @author Brunoggdev
     */
    public static function delete(string $uri, string|array|callable $acao)
    {
        return self::instancia()->adicionar('DELETE', $uri, $acao);
    }


    /**
     * Adiciona uma nova rota no array de rotas
     * @author Brunoggdev
     */
    protected function adicionar(string $verbo_http, string $uri, string|array|callable $acao): self
    {
        static::$rotas[] = [
            'uri' => preg_replace('/\{[^}]+\}/', '(.*)', strip_tags($uri)),
            'verbo_http' => $verbo_http,
            'acao' => $this->formatarAcao($acao),
            'filtro' => ''
        ];

        return $this;
    }


    /**
     * Formata a acao como um callable independente do formato inicial
     * @author Brunoggdev
     */
    protected function formatarAcao(string|array|callable $acao): callable
    {
        if ($acao instanceof \Closure) {
            return $acao;
        }

        [$controller, $metodo] = is_string($acao) ? explode('::', $acao) : $acao;

        // Adicionando namespace padrão pro caso de ação informada como string
        if (!str_contains($controller, '\\')) {
            $controller = $this->namespace_padrao . '\\' . str_replace('/', '\\', $controller);
        }

        return function (...$params) use ($controller, $metodo) {
            return controller($controller)->$metodo(...$params);
        };
    }


    /**
     * Configura o namespare padrão para os metodos do controller
     * @author Brunoggdev
     */
    public static function namespacePadrao(string $namespace): void
    {
        self::$namespace_padrao = $namespace;
    }


    /**
     * Adiciona o filtro especificado na rota em que foi chamado;
     * @author Brunoggdev
     */
    public function filtro(string $filtro): void
    {
        self::$rotas[array_key_last(self::$rotas)]['filtro'] = $filtro;
    }


    /**
     * Realiza o agrupamento de diversas rotas sob um mesmo filtro.
     * @author Brunoggdev
     */
    public static function agrupar(string $filtro, callable $callback): void
    {
        $rotas_antigas = self::$rotas;

        $callback(self::instancia());

        $novas_rotas = array_diff_key(self::$rotas, $rotas_antigas);

        foreach ($novas_rotas as $key => $rota) {
            self::$rotas[$key]['filtro'] = $filtro;
        }
    }

    /**
     * Tenta mapear a uri requisitada com uma das rotas configuradas e, 
     * caso encontre, retorna a sua ação e seus parametros;
     * @author Brunoggdev
     */
    public function mapear(string $uri, string $verbo_http): array
    {
        foreach (static::$rotas as $rota) {
            $verbo_http_corresponde = $rota['verbo_http'] === strtoupper($verbo_http);
            $uri_corresponde = preg_match('#^/?' . rtrim($rota['uri'], '/') . '$#', rtrim($uri, '/'), $params);

            if ($verbo_http_corresponde && $uri_corresponde) {
                (new Filtros)->filtrar($rota['filtro']);
                return [$rota['acao'], array_slice($params, 1)];
            }
        }

        $codigo_http = 404;
        abortar($codigo_http, view($codigo_http));
    }
}
