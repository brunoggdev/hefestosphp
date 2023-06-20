<?php

namespace App\Models;

use Hefestos\ModelBase;

class TestesModel extends ModelBase
{
    // tabela do banco de dados ao qual o model está relacionado
    protected $tabela = 'pets';

    /**
     * Tip > Describe what you want your method to do first
     * @author Brunoggdev
    */
    public function pets()
    {
        return $this->tudo();
    }
}