<?php

namespace App\Models;

use System\Core\Classes\Database;

class ModelBase 
{
    // tabela do banco de dados ao qual o model está relacionado
    protected $tabela;


    /**
    * Atalho para interagir com o método select do query builder
    * @author Brunoggdev
    */
    public function select(array $colunas = ['*']):Database
    {
        return (new Database)->select($this->tabela, $colunas);
    }


    /**
    * Atalho para interagir com o método insert do query builder
    * @author Brunoggdev
    */
    public function insert(array $params):bool
    {
        return (new Database)->insert($this->tabela, $params);
    }


    /**
    * Atalho para interagir com o método update do query builder
    * e editar um único id
    * @author Brunoggdev
    */
    public function update(int|string $id, array $params):bool
    {
        return (new Database)->update($this->tabela, $params, ['id' => $id]);
    }


    /**
    * Atalho para interagir com o método delete do query builder
    * e deletar um único id
    * @author Brunoggdev
    */
    public function delete(int|string $id)
    {
        return (new Database)->delete($this->tabela, ['id' => $id]);
    }



    /**
    * Retorna uma instancia da classe Database.
    * @author Brunoggdev
    */
    public function db():Database
    {
        return new Database;
    }



    /**
    * Retorna os erros que ocorreram durante a execução da SQL
    * @author Brunoggdev
    */
    public function erros():array
    {
        return (new Database)->erros();
    }
}