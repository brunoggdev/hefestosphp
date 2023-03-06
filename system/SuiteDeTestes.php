<?php

namespace System;


/**
* Guarda uma suíte de testes a serem executados pela CLI
* @author Brunoggdev
*/
class SuiteDeTestes
{
    protected $testes = [];


    /**
    *  Adiciona um novo teste na suíte de testes
    * @author Brunoggdev
    */
    public function se(string $descricao, callable $teste)
    {
        $this->testes[] = [
            'descricao' => $descricao,
            'funcao' => $teste
        ];
    }


    /**
    * Retorna todos os testes
    * @author Brunoggdev
    */
    public function testes():array
    {
        return $this->testes;
    }
}
