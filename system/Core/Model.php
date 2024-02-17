<?php

namespace Hefestos\Core;

use Hefestos\Database\Database;

class Model 
{
    /** Tabela do banco de dados ao qual o model está relacionado */
    protected string $tabela;

    /** Indica o tipo de retorno pela database ('array' ou 'objeto') */
    protected string $retorno_padrao;

    /** instancia do banco de dados */
    private Database $db;

    /**
     * Pode receber uma conexão alternativa com o banco para utilizar na model
     * invés da conexão padrão do sistema.
     * @author Brunoggdev
    */
    public function __construct(Database $db = null)
    {
        if (! is_null($db)) {
            $this->db = $db;
        }

        if (isset($this->retorno_padrao) && $this->retorno_padrao == 'objeto') {
            $this->comoColecao();
        }
    }


    /**
    * Retorna todas as linhas do Model em questão com todas as colunas ou colunas especificas
    * @author Brunoggdev
    */
    public function buscarTodos(?array $colunas = ['*']):mixed
    {
        return $this->select($colunas)->todos();
    }


    /**
     * Retorna a linha com o id informado ou, opcionalmente, uma coluna especifica.
     * @author Brunoggdev
    */
    public function buscar(int|string $id, ?string $coluna = null):mixed
    {
        return $this->where(['id' => $id])->primeiro($coluna);
    }


    /**
    * Atalho para interagir com o método select do query builder
    * @author Brunoggdev
    */
    public function select(?array $colunas = ['*']):Database
    {
        return $this->db()->select($this->tabela, $colunas);
    }


    /**
    * Atalho para interagir com o método where do query builder
    * @author Brunoggdev
    */
    public function where(array|string $params):Database
    {
        return $this->select()->where($params);
    }


    /**
     * Atalho para interagir com o método insert do query builder;
     * Retorna o id inserido (por padrão) ou um bool para sucesso ou falha.
     * @author Brunoggdev
    */
    public function insert(array $params, bool $retornar_id = true):string|bool
    {
        return $this->db()->insert($this->tabela, $params, $retornar_id);
    }


    /**
     * Atalho para interagir com o método update do query builder
     * e editar um registro, sendo a condição padrão o id;
     * @return bool true se sucesso, false caso contrário;
     * @author Brunoggdev
    */
    public function update(int|string|array $condicao, array $params):bool
    {
        $where = is_array($condicao) ? $condicao : ['id' => $condicao];

        return $this->db()->update($this->tabela, $params, $where);
    }


    /**
    * Atalho para interagir com o método delete do query builder
    * e deletar um único id
    * @author Brunoggdev
    */
    public function delete(int|string|array $condicao)
    {
        $where = is_array($condicao) ? $condicao : ['id' => $condicao];

        return $this->db()->delete($this->tabela, $where);
    }


    /**
     * Retorna o último id inserido pela sql mais recente
     * @author Brunoggdev
    */
    public function idInserido():string|false
    {
        return $this->db()->idInserido();
    }


    /**
    * Retorna os erros que ocorreram durante a execução da SQL
    * @author Brunoggdev
    */
    public function erros():array
    {
        return $this->db()->erros();
    }


    /**
    * Define que o retorno da Database será um array associativo
    * @author Brunoggdev
    */
    public function comoArray():self
    {
        $this->db()->comoArray();

        return $this;
    }


    /**
    * Define que o retorno da Database será uma instacia de Colecao
    * @author Brunoggdev
    */
    public function comoColecao():self
    {
        $this->db()->comoColecao();

        return $this;
    }


    /**
     * Retorna a instancia do query builder conecato ao banco de dados
     * @author Brunoggdev
    */
    public function db():Database
    {
        if (! isset($this->db)) {
            $this->db = Database::instancia();
        }

        return $this->db;
    }
}