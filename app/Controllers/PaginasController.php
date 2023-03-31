<?php

namespace App\Controllers;

use App\Models\Usuario;

class PaginasController extends ControllerBase
{
    public function index()
    {
        return json([]);
    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function teste():string
    {
        dd('teste');
    }
}
