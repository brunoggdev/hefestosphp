<?php

namespace App\Controllers;

use App\Models\Usuario;
use System\Core\Classes\Database;

class PaginasController extends ControllerBase
{
    public function index()
    {
        $usuario = new Usuario;
        $usuario->update(1, ['usuario' => 'testssse']);
        
        dd((new Usuario)->todos());

    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function teste($teste, $testa):string
    {
        return '';
    }
}
