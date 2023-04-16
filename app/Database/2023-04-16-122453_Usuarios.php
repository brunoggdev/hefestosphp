<?php
namespace App\Database;

use System\Database\Tabela;

// Você deve retornar a sql para criar uma tabela como desejar.
// Pode ser utilizando a classe Tabela ou mesmo uma string pura.
return ( new Tabela('usuarios') )
    ->id()
    ->string('nome_completo')
    ->string('usuario')
    ->string('senha')
    ->string('email', true) // true para ser único
    ->boolean('admin')
    ->boolean('ativo');
