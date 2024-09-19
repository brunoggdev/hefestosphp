<?php

namespace Hefestos\Rotas;

/**
 * Controla todo o sistema de roteamento da aplicação
 * @author Brunoggdev
 */
class Roteador
{

    protected static ?self $instancia = null;
    protected $rotas = [];
    protected $namespace_padrao = '\App\Controllers';

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
    public function get(string $uri, string|array|callable $acao)
    {
        $this->adicionar('GET', $uri, $acao);
        return $this;
    }



    /**
     * Adiciona uma rota post no array de rotas
     * @author Brunoggdev
     */
    public function post(string $uri, string|array|callable $acao)
    {
        $this->adicionar('POST', $uri, $acao);
        return $this;
    }



    /**
     * Adiciona uma rota put no array de rotas
     * @author Brunoggdev
     */
    public function put(string $uri, string|array|callable $acao)
    {
        $this->adicionar('PUT', $uri, $acao);
        return $this;
    }



    /**
     * Adiciona uma rota patch no array de rotas
     * @author Brunoggdev
     */
    public function patch(string $uri, string|array|callable $acao)
    {
        $this->adicionar('PATCH', $uri, $acao);
        return $this;
    }



    /**
     * Adiciona uma rota delete no array de rotas
     * @author Brunoggdev
     */
    public function delete(string $uri, string|array|callable $acao)
    {
        $this->adicionar('DELETE', $uri, $acao);
        return $this;
    }


    /**
     * Adiciona uma nova rota no array de rotas
     * @author Brunoggdev
     */
    protected function adicionar(string $verbo_http, string $uri, string|array|callable $acao): void
    {
        $this->rotas[] = [
            'uri' => preg_replace('/\{[^}]+\}/', '(.*)', strip_tags($uri)),
            'verbo_http' => $verbo_http,
            'acao' => $this->formatarAcao($acao),
            'filtro' => ''
        ];
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
            return (new $controller)->$metodo(...$params);
        };
    }


    /**
     * Configura o namespare padrão para os metodos do controller
     * @author Brunoggdev
     */
    public function namespacePadrao(string $namespace): void
    {
        $this->namespace_padrao = $namespace;
    }


    /**
     * Adiciona o filtro especificado na rota em que foi chamado;
     * @author Brunoggdev
     */
    public function filtro(string $filtro): void
    {
        $this->rotas[array_key_last($this->rotas)]['filtro'] = $filtro;
    }


    /**
     * Realiza o agrupamento de diversas rotas sob um mesmo filtro.
     * @author Brunoggdev
     */
    public function agrupar(string $filtro, callable $callback): void
    {
        $rotas_antigas = $this->rotas;

        $callback($this);

        $novas_rotas = array_diff_key($this->rotas, $rotas_antigas);

        foreach ($novas_rotas as $key => $rota) {
            $this->rotas[$key]['filtro'] = $filtro;
        }
    }

    /**
     * Tenta mapear a uri requisitada com uma das rotas configuradas e, 
     * caso encontre, retorna a sua ação e seus parametros;
     * @author Brunoggdev
     */
    public function mapear(string $uri, string $verbo_http): array
    {
        foreach ($this->rotas as $rota) {
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
