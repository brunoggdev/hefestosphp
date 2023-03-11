<?php

namespace System\Testes;


/**
* Guarda uma suíte de testes a serem executados pela CLI
* @author Brunoggdev
*/
class SuiteDeTestes
{
    protected $testes = [];


    /**
    *  Adiciona um novo teste na suíte de testes
    * @param string $descricao Comentário a ser exibido no console durante os testes.
    * @param closure $teste Função de teste que deve sempre retornar um bool (como as esperas).
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
