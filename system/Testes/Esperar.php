<?php

namespace Hefestos\Testes;

class Esperar
{
    
    protected bool $negar = false;

    public function __construct(protected mixed $itemDeTeste)
    {
    }


    /**
     * Processa o retorno, reseta a negação e retorna a instancia;
     * @author Brunoggdev
    */
    protected function processarRetorno(bool $retorno, string $mensagem):self
    {
        if ($this->negar) {
            $retorno = !$retorno;
            $this->negar = false;
        }

        if (!$retorno) {
            throw new \Exception($mensagem, 420);
        }

        return $this;
    }

    /**
     * Nega qualquer que seja o resultado da próxima chamada
     * @author Brunoggdev
    */
    public function nao():self
    {
        $this->negar = true;
        return $this;
    }


    /**
     *  Verifica se o tipo do item de teste é Bool
     * @author Brunoggdev
    */
    public function serBool()
    {
        $retorno = is_bool($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é booleano.");
    }


    /**
     * Verifica se o item é um número (inteiro, float ou string numérica)
     * @author Brunoggdev
    */
    public function serNumero()
    {
        $retorno = is_numeric($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é numérico.");
    }


    /**
     * Verifica se o tipo do item de teste é inteiro
     * @author Brunoggdev
    */
    public function serInteiro()
    {
        $retorno = is_int($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é um inteiro.");
    }


    /**
     * Verifica se o tipo do item de teste é float
     * @author Brunoggdev
    */
    public function serFloat()
    {
        $retorno = is_float($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é um float.");
    }


    /**
     *  Verifica se o tipo do item de teste é string
     * @author Brunoggdev
    */
    public function serString()
    {
        $retorno = is_string($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é uma string.");
    }

    
    /**
     *  Verifica se o tipo do item de teste é array
     * @author Brunoggdev
    */
    public function serArray()
    {
        $retorno = is_array($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é um array.");
    }


    /**
     *  Verifica se o tipo do item de teste é objeto
     * @author Brunoggdev
    */
    public function serObjeto()
    {
        $retorno = is_object($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é um objeto.");
    }


    /**
     *  Verifica se o retorno do item de teste é true
     * @author Brunoggdev
    */
    public function serVerdadeiro()
    {
        $retorno = ($this->itemDeTeste === true);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é verdadeiro.");
    }


    /**
     *  Verifica se o retorno do item de teste é false
     * @author Brunoggdev
    */
    public function serFalso()
    {
        $retorno = ($this->itemDeTeste === false);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é falso.");
    }


    /**
     *  Verifica se o tipo do item de teste é null
     * @author Brunoggdev
    */
    public function serNulo()
    {
        $retorno = is_null($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é nulo.");
    }


    /**
     *  Verifica se o item de teste é uma função
     * @author Brunoggdev
    */
    public function serFuncao()
    {
        $retorno = is_callable($this->itemDeTeste);
        
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é uma função.");
    }


    /**
     *  Verifica se o item de teste possui o desejado 
     * (parte de string, item ou chave de array, atributos, constantes ou métodos de objeto)
     * @author Brunoggdev
    */
    public function conter(string|array|object $agulha)
    {
        $retorno = match (gettype($this->itemDeTeste)) {
            'string' => str_contains($this->itemDeTeste, $agulha),
            'array' => in_array($agulha, $this->itemDeTeste) || array_key_exists($agulha, $this->itemDeTeste),
            'object' => (
                in_array($agulha, ($object = (new \ReflectionObject($this->itemDeTeste)))->getAttributes())
                ||
                in_array($agulha, $object->getConstants())
                ||
                in_array($agulha, $object->getMethods())
            ),
            default => false
        };
        
        return $this->processarRetorno($retorno, "Falha ao conferir que o ítem possua o conteúdo desejado.");
    }


    /**
     *  Verifica se o item de teste corresponde ao item desejado
     * @author Brunoggdev
    */
    public function serIgual(mixed $itemDeComparacao)
    {
        $retorno = $this->itemDeTeste === $itemDeComparacao;
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é igual a {$itemDeComparacao}.");
    }


    /**
     * Verifica se o item de teste exite
     * @author Brunoggdev
    */
    public function existir()
    {
        $retorno = isset($this->itemDeTeste);
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} existe.");
    }


    /**
     * Verifica se o item de teste exite
     * @author Brunoggdev
    */
    public function serArquivo()
    {
        $retorno = is_file($this->itemDeTeste);
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é um arquivo válido.");
    }

    /**
     * Verifica se o item de teste exite
     * @author Brunoggdev
    */
    public function serDiretório()
    {
        $retorno = is_dir($this->itemDeTeste);
        return $this->processarRetorno($retorno, "Falha ao conferir que {$this->itemDeTeste} é um diretório válido.");
    }
}
