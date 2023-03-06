<?php

namespace App\Models;

class Usuario extends ModelBase
{
    protected $tabela = 'usuarios';


    /**
    * Retorna todos os usuarios
    * @author Brunoggdev
    */
    public function todos():array
    {
        return $this->select()->where(['is_admin' => false])->todos();
    }

    public function novo($usuario):bool
    {
        return $this->insert($usuario);
    }
    
    public function editar($id, $usuario):bool
    {
        return $this->update($id, $usuario);
    }
}
