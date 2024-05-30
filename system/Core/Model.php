<?php

namespace Hefestos\Core;

use Hefestos\Database\Database;

abstract class Model 
{
    /** Tabela do banco de dados ao qual o model está relacionado */
    protected string $tabela;

    /** Indica o tipo de retorno pela database - 'array' ou 'nome qualificado' da classe desejada (SuaClasse::class) */
    protected string $retorno_padrao;


    /**
     * Pode receber uma conexão alternativa com o banco para utilizar na model
     * invés da conexão padrão do sistema.
     * @author Brunoggdev
    */
    public function __construct(private ?Database $db = null)
    {
        if (isset($this->retorno_padrao) && class_exists($this->retorno_padrao)) {
            $this->comoObjeto($this->retorno_padrao);
        }
    }


    /**
    * Retorna todas as linhas do Model em questão com todas as colunas ou colunas especificas
    * @author Brunoggdev
    */
    public function buscarTodos(array $colunas = ['*'], bool $coluna_unica = false):mixed
    {
        return $this->db()->buscarTodos($colunas, $coluna_unica);
    }


    /**
     * Retorna toda a linha (ou coluna especifica) com o id informado.
     * @author Brunoggdev
    */
    public function buscar(int|string $id, ?string $coluna = null):mixed
    {
        return $this->db()->buscar($id, $coluna);
    }


    /**
     * Retorna o primeiro resultado para o 'where' informado
    */
    public function primeiroOnde(array|string $where):mixed
    {
        return $this->db()->primeiroOnde($where);
    }


    /**
    * Atalho para interagir com o método select do query builder
    * @author Brunoggdev
    */
    public function select(array $colunas = ['*']):Database
    {
        return $this->db()->select($colunas);
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
    public function insert(array|object $params, bool $retornar_id = true):string|bool
    {
        return $this->db()->insert($params, $retornar_id);
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

        return $this->db()->update($params, $where);
    }


    /**
    * Atalho para interagir com o método delete do query builder.
    * Recebe o id da linha desejada para deletar ou uma condição diferente se desejar
    * @author Brunoggdev
    */
    public function delete(int|string|array $condicao)
    {
        $where = is_numeric($condicao) ? ['id' => $condicao] : $condicao;

        return $this->db()->delete($where);
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
     * Define o retorno do banco de dados como um objeto (ou array de objetos) da classe informada;
     * O array de resultados será passado para o construtor da classe desejada.
     * @param string $classe SuaClasse::class - O "nome qualificado" da classe desejada
     * @author Brunoggdev
    */
    public function comoObjeto(string $classe):self
    {
        $this->db()->comoObjeto($classe);

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
            $this->db->tabela($this->tabela);
        }

        return $this->db;
    }
}