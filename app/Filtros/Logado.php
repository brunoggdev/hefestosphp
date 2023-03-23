<?php

namespace App\Filtros;

class Logado
{
    /**
    * Aplica o filtro configurado
    * @author Brunoggdev
    */
    public function aplicar():void
    {
        redirecionar('/bler');
    }
}
