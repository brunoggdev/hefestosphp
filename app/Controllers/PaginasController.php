<?php

namespace App\Controllers;

class PaginasController extends ControllerBase
{
    public function index(){

        sessao()->guardar('teste','vamo la');
        if( $this->reqGet('true') === 'red' ){
            sessao()->flash('teste','redirecionarndp');
            redirecionar('t');
        }
        return sessao('teste');

    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function teste():string
    {
        return sessao('teste')?? 'aa';
    }
}
