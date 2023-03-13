<?php

use System\Testes\Esperar;

$testar = new \System\Testes\SuiteDeTestes();

/**
* passe qualquer tipo de parametro à ser testado aqui 
* (String, array, objeto, condição, etc).
* Atalho para interair com a classe Esperar.
* @author Brunoggdev
*/
function esperar(mixed $item):Esperar
{
    return new Esperar($item);
}