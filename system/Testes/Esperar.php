<?php

namespace System\Testes;

use Closure;
use ReflectionObject;

class Esperar
{
    
    protected bool $negar = false;

    public function __construct(protected mixed $itemDeTeste)
    {
    }


    /**
    *  Verifica se o tipo do item de teste é Bool
    * @author Brunoggdev
    */
    public function serBool():bool
    {
        $retorno = gettype($this->itemDeTeste) === 'bool';
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Verifica se o tipo do item de teste é String
    * @author Brunoggdev
    */
    public function serString():bool
    {
        $retorno = gettype($this->itemDeTeste) === 'string';
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Verifica se o tipo do item de teste é Objeto
    * @author Brunoggdev
    */
    public function serObjeto():bool
    {
        $retorno = gettype($this->itemDeTeste) === 'object';
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Verifica se o retorno do item de teste é true
    * @author Brunoggdev
    */
    public function serVerdadeiro():bool
    {
        $retorno = ($this->itemDeTeste === true);
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Verifica se o retorno do item de teste é false
    * @author Brunoggdev
    */
    public function serFalso():bool
    {
        $retorno = ($this->itemDeTeste === false);
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Verifica se o tipo do item de teste é null
    * @author Brunoggdev
    */
    public function serNulo():bool
    {
        $retorno = gettype($this->itemDeTeste) === 'null';
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Verifica se o tipo do item de teste é array
    * @author Brunoggdev
    */
    public function serClosure():bool
    {
        $retorno = $this->itemDeTeste instanceof Closure;
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Verifica se o tipo do item de teste é array
    * @author Brunoggdev
    */
    public function serArray():bool
    {
        $retorno = gettype($this->itemDeTeste) === 'array';
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Tip > Describe what you want your method to do first
    * @author Brunoggdev
    */
    public function conter(string|array|object $agulha):bool
    {
        $retorno = match (gettype($this->itemDeTeste)) {
            'string' => str_contains($this->itemDeTeste, $agulha),
            'array' => in_array($agulha, $this->itemDeTeste),
            'object' => (
                in_array($agulha, ($object = (new \ReflectionObject($this->itemDeTeste)))->getAttributes())
                ||
                in_array($agulha, $object->getConstants())
                ||
                in_array($agulha, $object->getMethods())
            ),
            default => false
        };
        
        return $this->negar ? !$retorno : $retorno;
    }


    /**
    *  Nega qualquer que seja o resultado da próxima chamada
    * @author Brunoggdev
    */
    public function nao():self
    {
        $this->negar = true;
        return $this;
    }


    /**
    *  Verifica se o item de teste corresponde ao item desejado
    * @author Brunoggdev
    */
    public function ser(mixed $itemDeComparacao):bool
    {
        $retorno = $this->itemDeTeste === $itemDeComparacao;
        return $this->negar ? !$retorno : $retorno;
    }
}
