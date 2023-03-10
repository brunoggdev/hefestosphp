<?php

namespace App\Controllers;

use System\Core\Classes\Redirecionar;

class PaginasController extends ControllerBase
{
    public function index():string|Redirecionar
    {
        // return view();
        // ou
        // return json();
        // ou ainda
        // return redirecionar(); 
    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function teste():string
    {
        return sessao('flash')?? 'aa';
    }
}
