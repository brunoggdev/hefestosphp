<?php

namespace App\Models;

use Hefestos\Model;

class ExemploModel extends Model
{
    // tabela do banco de dados ao qual o model está relacionado
    protected string $tabela = 'pets';

public function novoPet()
{
    $pet = [
        'nome' => 'Garry',
        'dono' => 'Bob Esponja',
        'vip' => true,
        'data_cadastro' => 'CURRENT_TIMESTAMP'
    ];
	return $this->insert($pet);
}
}