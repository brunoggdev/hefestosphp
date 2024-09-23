<?php

namespace Hefestos\Core;

use Hefestos\Database\Database;

abstract class Model
{
    protected ?Database $db = null;

    /** Tabela do banco de dados ao qual o model está relacionado */
    protected string $tabela;

    /** Indica o tipo de retorno pela database - 'array' ou 'nome qualificado' da classe desejada (SuaClasse::class) */
    protected string $retorno_padrao;


    /**
     * Pode receber uma conexão alternativa com o banco para utilizar na model
     * invés da conexão padrão do sistema.
     * @author Brunoggdev
     */
    public function __construct(?Database $db = null)
    {
        if (!is_null($db)) {
            $this->db = $db;
        }
    }


    /**
     * Retorna todas as linhas do Model em questão com todas as colunas
     * @author Brunoggdev
     */
    public function todos(bool $coluna_unica = false): mixed
    {
        return $this->db()->todos($coluna_unica);
    }


    /**
     * Retorna toda a linha (ou coluna especifica) com o id informado.
     * @author Brunoggdev
     */
    public function buscar(int|string $id, ?string $coluna = null): mixed
    {
        return $this->db()->buscar($id, $coluna);
    }


    /**
     * Retorna o primeiro resultado para o 'where' informado; Opcionalmente, recebe o nome 
     * de coluna especifica para que apenas os dados dessa coluna sejam retornados.
     */
    public function primeiroOnde(array|string $where, ?string $nome_coluna_especifica = null): mixed
    {
        return $this->db()->primeiroOnde($where, $nome_coluna_especifica);
    }


    /**
     * Atalho para interagir com o método select do query builder
     * @author Brunoggdev
     */
    public function select(array $colunas = ['*']): Database
    {
        return $this->db()->select($colunas);
    }


    /**
     * Atalho para interagir com o método where do query builder
     * @author Brunoggdev
     */
    public function where(array|string $params, mixed $valor = null): Database
    {
        return $this->db()->where($params, $valor);
    }


    /**
     * Atalho para interagir com o método insert do query builder;
     * Retorna o id inserido (por padrão) ou um bool para sucesso ou falha.
     * @author Brunoggdev
     */
    public function insert(array|object $params, bool $retornar_id = true): string|bool
    {
        if ($params instanceof Entidade) {
            $entidade = $params;
            $params = $entidade->paraArray();
            extrair_item($entidade->chavePrimaria(), $params);
        }
        
        return $this->db()->insert($params, $retornar_id);
    }

    
    /**
     * Atalho para interagir com o metodo set() do query builder (objetos tentarão ser convertidos para array)
     */
    public function set(array|object $params): Database
    {
        if ($params instanceof Entidade) {
            $params = $params->paraArray();
        }

        return $this->db()->set($params);
    }


    /**
     * Atalho para interagir com o método update() do query builder
     * e editar um registro, sendo a condição padrão o id;
     * @return bool true se sucesso, false caso contrário;
     * @author Brunoggdev
     */
    public function update(array|object $params, array|string|int $where = []): bool
    {

        if (is_numeric($where)) {
            $where = ['id' => $where];
        }


        if ($params instanceof Entidade) {
            $entidade = $params;
            $params = $params->paraArray();

            if (empty($where)) {
                $where = [
                    $entidade->chavePrimaria() => $entidade->paraArray()[$entidade->chavePrimaria()] ?? null
                ];
            }
        }


        return $this->db()->update($params, $where);
    }


    /**
     * Atalho para interagir com o método delete do query builder.
     * Se apenas um número for informado na condição será assumido como da coluna "id"
     * @author Brunoggdev
     */
    public function delete(int|string|array|object $condicao)
    {   
        $where = match (true) {
            $condicao instanceof Entidade => [$condicao->chavePrimaria() => $condicao->paraArray()[$condicao->chavePrimaria()] ?? null],
            is_numeric($condicao) => ['id' => $condicao],
            default => $condicao
        };

        return $this->db()->delete($where);
    }


    /**
     * Retorna o último id inserido pela sql mais recente
     * @author Brunoggdev
     */
    public function idInserido(): string|false
    {
        return $this->db()->idInserido();
    }


    /**
     * Retorna os erros que ocorreram durante a execução da SQL
     * @author Brunoggdev
     */
    public function erros(): array
    {
        return $this->db()->erros();
    }


    /**
     * Define que o retorno da Database será um array associativo
     * @author Brunoggdev
     */
    public function comoArray(): self
    {
        $this->db()->comoArray();

        return $this;
    }


    /**
     * Define o retorno do banco de dados como um objeto (ou array de objetos) da classe informada;
     * O array de resultados será passado para o construtor da classe desejada.
     * @param string $classe SuaClasse::class - O "nome qualificado" da classe desejada
     * @author Brunoggdev
     */
    public function comoObjeto(string $classe): self
    {
        $this->db()->comoObjeto($classe);

        return $this;
    }


    /**
     * Retorna a instancia do query builder conecato ao banco de dados
     * @author Brunoggdev
     */
    public function db(): Database
    {
        if (!is_null($this->db)) {
            return $this->db;
        }

        $this->db = Database::instancia();

        $this->db->tabela($this->tabela);

        if (isset($this->retorno_padrao) && class_exists($this->retorno_padrao)) {
            $this->db->comoObjeto($this->retorno_padrao);
        }

        return $this->db;
    }
}
