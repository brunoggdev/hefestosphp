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
        if (property_exists($this->suite, $propriedade)) {
            return $this->suite->$propriedade;
        }
        return null;
    }

    public function testar(callable $teste):bool
    {
        return call_user_func($teste);
    }

    
    /**
    * Retorna todos os testes
    * @author Brunoggdev
    */
    public function testes():array
    {
        return $this->suite->testes;
    }
}