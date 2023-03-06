<?php

$testar = new \System\SuiteDeTestes();

function confereVerdadeiro(mixed $condicao)
{
    if(! $condicao ){
        return false;
    }
    
    return true;
}
