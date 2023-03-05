<?php

namespace App\Models;

use App\Database\Database;

class ModelBase 
{
    protected $tabela;

    /**
    * Adiciona um SELECT na consulta para a tabela do model
    * @author Brunoggdev
    */
    public function select(array $colunas = ['*']):Database
    {
        return (new Database)->select($this->tabela, $colunas);
    }



    /**
    * Adiciona um INSERT na consulta para a tabela do model
    * @author Brunoggdev
    */
    public function insert(array $params = ['*']):bool
    {
        return (new Database)->insert($this->tabela, $params);
    }

}