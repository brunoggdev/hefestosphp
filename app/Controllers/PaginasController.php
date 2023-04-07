<?php

namespace App\Controllers;

use App\Models\Usuario;

class PaginasController extends ControllerBase
{
    public function index()
    {
        dd(
            coletar((new Usuario)->comoArray()->tudo())->eachAssoc(function($key, $value){
                echo $value['nome'];
            })
        );
    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function teste():string
    {
        dd($this->dadosPost());
    }
}
