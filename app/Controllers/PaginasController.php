<?php

namespace App\Controllers;

use App\Models\Usuario;

class PaginasController extends ControllerBase
{
    public function index()
    {
        $result = (new Usuario);
        
        try{
            $resultado = $result->select()
            ->join('pets', 'usuarios.id = pets.id', 'inner')
            ->where(['usuarios.id' => 2])
            ->orWhere(['usuario' => 'Like usuario%'])
            ->todos();
        }catch(\Throwable $th){
            throw $th;
            $a = $result->erros();
        }
       dd( 

            $resultado

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
