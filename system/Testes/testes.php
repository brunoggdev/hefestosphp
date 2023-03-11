<?php

use System\Testes\Esperar;

$testar = new \System\Testes\SuiteDeTestes();

function confereVerdadeiro(mixed $condicao)
{
    if(! $condicao ){
        return false;
    }
    
    return true;
}


/**
* Atalho para interair com a classe Esperar
* @author Brunoggdev
*/
function esperar(mixed $item):Esperar
{
    return new Esperar($item);
}