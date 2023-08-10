<?php

namespace Hefestos;

use Hefestos\Database\Database;

class ModelBase 
{
    // tabela do banco de dados ao qual o model está relacionado
    protected $tabela;

    // tabela do banco de dados ao qual o model está relacionado
    protected $tipo_retorno_padrao;

    // instancia do banco de dados
    private $db;

    /**
    * Cria uma instancia do banco de dados que será utilizada nas consultas
    * @author Brunoggdev
    */
    public function __construct(Database $db = null)
    {
        if (! is_null($db)) {
            $this->db = $db;
        }

        if ($this->tipo_retorno_padrao == 'objeto') {
            $this->comoColecao();
        }
    }


    /**
    * Retorna todas as linhas do Model em questão com todas as colunas ou colunas especificas
    * @author Brunoggdev
    */
    public function tudo(?array $colunas = ['*']):mixed
    {
        return $this->select($colunas)->todos();
    }


    /**
    * Retorna a linha com o id ou array de condição informado
    * e, opcionalmente, uma coluna especifica.
    * @author Brunoggdev
    */
    public function buscar(int|string|array $busca, ?string $coluna = null):mixed
    {
        if(is_array($busca)){
            return $this->where($busca)->primeiro($coluna);
        }

        return $this->where(['id' => $busca])->primeiro($coluna);
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
    * Atalho para interagir com o método insert do query builder
    * @author Brunoggdev
    */
    public function insert(array $params):bool
    {
        return $this->db()->insert($this->tabela, $params);
    }


    /**
    * Atalho para interagir com o método update do query builder
    * e editar um único id
    * @author Brunoggdev
    */
    public function update(int|string $id, array $params):bool
    {
        return $this->db()->update($this->tabela, $params, ['id' => $id]);
    }


    /**
    * Atalho para interagir com o método delete do query builder
    * e deletar um único id
    * @author Brunoggdev
    */
    public function delete(int|string $id)
    {
        return $this->db()->delete($this->tabela, ['id' => $id]);
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
            $this->db = Database::singleton();
        }
        
        return $this->db;
    }
}