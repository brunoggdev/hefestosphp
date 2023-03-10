<?php

namespace App\Controllers;

class PaginasController extends ControllerBase
{
    public function index(){

        sessao()->guardar('teste','vamo la');
        if( $this->reqGet('true') === 'true' ){
            sessao()->flash('teste','redirecionarndp');
            redirecionar('t')->com('flash', 'FLASHADO BOYYYY');
        }
        return sessao('teste');

    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function teste($teste, $testa):string
    {
        echo $teste;
        echo '<br>';
        echo $testa;

        return sessao('flash')?? 'aa';
    }
}
