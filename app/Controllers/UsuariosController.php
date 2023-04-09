<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use System\Rotas\Redirecionar;

class UsuariosController extends ControllerBase
{
    public function login()
    {
        $usuario = $this->dadosPost('usuario');
        $senha = $this->dadosPost('senha');

        $usuarioAutenticado = (new UsuariosModel)->autenticar($usuario, $senha);

        if (! $usuarioAutenticado) {
            return redirecionar('login')->com('mensagem', [
                'texto' => 'Usuario e/ou senha inválidos.',
                'cor' => 'danger'
            ]);
        }
        
        $usuarioAutenticado['logado'] = true;

        sessao()->guardar('usuario', $usuarioAutenticado);
        
        return redirecionar('home')->com('mensagem', [
            'texto' => 'Login efetuado com sucesso.',
            'cor' => 'success'
        ]);  
    }


    /**
    * Destroi a sessão e redireciona para pagina de login
    * @author Brunoggdev
    */
    public function logout()
    {
        sessao()->limpar('usuario');
        
        return redirecionar('login')->com('mensagem', [
            'texto' => 'Logout efetuado com sucesso.',
            'cor' => 'success'
        ]);
    }
}
