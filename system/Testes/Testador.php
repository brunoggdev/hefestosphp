<?php

namespace Hefestos\Testes;

/**
* Guarda uma suíte de testes a serem executados pela CLI
* @author Brunoggdev
*/
class Testador extends SuiteDeTestes
{
    /**
     * Inicia o testador
     * @author Brunoggdev
    */
    public function __construct(private SuiteDeTestes $suite)
    {
    }

    public function __get(mixed $propriedade) {
        return $this->suite::$propriedades[$propriedade] ?? null;
    }

    public function testar(callable $teste)
    {
        return $teste();
    }

    
    /**
    * Retorna todos os testes
    * @author Brunoggdev
    */
    public function testes():array
    {
        return $this->suite::$testes;
    }
}