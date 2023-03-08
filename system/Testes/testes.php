<?php

$testar = new \System\Testes\SuiteDeTestes();

function confereVerdadeiro(mixed $condicao)
{
    if(! $condicao ){
        return false;
    }
    
    return true;
}
