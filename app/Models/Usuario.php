<?php

namespace App\Models;

class Usuario extends ModelBase
{
    protected $tabela = 'usuarios';




    public function novo($usuario):bool
    {
        return $this->insert($usuario);
    }
    
    public function editar($id, $usuario):bool
    {
        return $this->update($id, $usuario);
    }
}
