<?php

namespace App\Models;

class UsuariosModel extends ModelBase
{
    // tabela do banco de dados ao qual o model está relacionado
    protected $tabela = 'usuarios';


    /**
    * Tenta autenticar o usuário no banco de dados
    * @author Brunoggdev
    */
    public function autenticar(string $usuario, string $senha):bool
    {
        $usuario = $this->where(['usuario' => $usuario, 'ativo' => true])->primeiro();

        if ( empty($usuario)  ||  !password_verify($senha, $usuario['senha']) ) {
            return false;
        }

        // removendo a senha do array antes de devolver pro controller
        unset($usuario['senha']);

        return $usuario;
    }
}
