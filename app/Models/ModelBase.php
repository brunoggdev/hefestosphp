<?php

namespace App\Models;

use App\Database\Database;

class ModelBase 
{
    protected $tabela;


    /**
    * Inicia uma sql de UPDATE para a tabela do model
    * @author Brunoggdev
    */
    public function select(array $colunas = ['*']):Database
    {
        return (new Database)->select($this->tabela, $colunas);
    }


    /**
    * Inicia uma sql de INSERT para a tabela do model
    * @author Brunoggdev
    */
    public function insert(array $params):bool
    {
        return (new Database)->insert($this->tabela, $params);
    }


    /**
    * Inicia uma sql de UPDATE para a tabela do model
    * @author Brunoggdev
    */
    public function update(int|string $id, array $params):bool
    {
        return (new Database)->update($this->tabela, $params, ['id' => $id]);
    }


    /**
    * Inicia uma sql de DELETE para a tabela do model
    * @author Brunoggdev
    */
    public function delete(array $colunas = ['*'])
    {
        //
    }



    /**
    * Retorna uma instancia da classe Database.
    * @author Brunoggdev
    */
    public function db():Database
    {
        return new Database;
    }
}