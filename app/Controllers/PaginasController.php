<?php

namespace App\Controllers;

use App\Models\Usuario;

class PaginasController extends ControllerBase
{
    public function index()
    {
        dd( 
            (new Usuario)->select()
            ->where(['id' => 2])
            ->orWhere(['usuario' => 'Like usuario%'])
            ->todos()
        );
    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function teste():string
    {
        dd( 
            (new Usuario)
            ->select()
            ->where(['id' => 3])
            ->orWhere(['usuario' => 'Like usuario%'])
            ->primeiro()
        );
    }
}
