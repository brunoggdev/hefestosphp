<?php

namespace App\Controllers;

use App\Models\Usuario;

class PaginasController extends ControllerBase
{
    public function index(){
        return view('home', [
            'saudacao' => 'Olá',
            'insert' => (new Usuario)->novo([
                'nome' => 'usuario comum jr',
                'email' => 'usuario3@exemplo.com',
                'usuario' => 'usuario3',
                'senha' => protegerSenha('1234')
            ]),
            'update' =>  (new Usuario)->editar('2', [
                    'nome' => 'usuario comum pleno 2',
                    'email' => 'usuario76@exemplo.com',
                ]),
            'usuarios' => (new Usuario)->todos()
        ]);
    }
}
