<?php

namespace Hefestos\Testes;

class Esperar
{
    
    protected bool $negar = false;

    public function __construct(protected mixed $item_de_teste)
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
            $negado = true;
            $this->negar = false;
        }

        if (!$retorno) {
            throw new \Exception(
                "Falha ao conferir que ". 
                $this->processarSaida().
                (isset($negado) ? " não " : " ").
                $mensagem, 1
            );
        }

        return $this;
    }


    /**
     * Processa o retorno, reseta a negação e retorna a instancia;
     * @author Brunoggdev
    */
    protected function processarSaida():mixed
    {
        $retorno = get_debug_type($this->item_de_teste);

        if (in_array($retorno, ['int', 'float', 'string'])) {
            return $retorno . " ({$this->item_de_teste})";
        }

        return $retorno;
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
        $retorno = is_bool($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é booleano.");
    }


    /**
     * Verifica se o item é um número (inteiro, float ou string numérica)
     * @author Brunoggdev
    */
    public function serNumero()
    {
        $retorno = is_numeric($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é numérico.");
    }


    /**
     * Verifica se o tipo do item de teste é inteiro
     * @author Brunoggdev
    */
    public function serInteiro()
    {
        $retorno = is_int($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é um inteiro.");
    }


    /**
     * Verifica se o tipo do item de teste é float
     * @author Brunoggdev
    */
    public function serFloat()
    {
        $retorno = is_float($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é um float.");
    }


    /**
     *  Verifica se o tipo do item de teste é string
     * @author Brunoggdev
    */
    public function serString()
    {
        $retorno = is_string($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é uma string.");
    }

    
    /**
     *  Verifica se o tipo do item de teste é array
     * @author Brunoggdev
    */
    public function serArray()
    {
        $retorno = is_array($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é um array.");
    }


    /**
     *  Verifica se o tipo do item de teste é objeto
     * @author Brunoggdev
    */
    public function serObjeto()
    {
        $retorno = is_object($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é um objeto.");
    }


    /**
     *  Verifica se o retorno do item de teste é true
     * @author Brunoggdev
    */
    public function serVerdadeiro()
    {
        $retorno = ($this->item_de_teste === true);
        
        return $this->processarRetorno($retorno, "é verdadeiro.");
    }


    /**
     *  Verifica se o retorno do item de teste é false
     * @author Brunoggdev
    */
    public function serFalso()
    {
        $retorno = ($this->item_de_teste === false);
        
        return $this->processarRetorno($retorno, "é falso.");
    }


    /**
     *  Verifica se o tipo do item de teste é null
     * @author Brunoggdev
    */
    public function serNulo()
    {
        $retorno = is_null($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é nulo.");
    }


    /**
     *  Verifica se o item de teste é uma função
     * @author Brunoggdev
    */
    public function serFuncao()
    {
        $retorno = is_callable($this->item_de_teste);
        
        return $this->processarRetorno($retorno, "é uma função.");
    }


    /**
     *  Verifica se o item de teste possui o desejado 
     * (parte de string, item ou chave de array, atributos, constantes ou métodos de objeto)
     * @author Brunoggdev
    */
    public function conter(string|array|object $agulha)
    {
        $retorno = match (gettype($this->item_de_teste)) {
            'string' => str_contains($this->item_de_teste, $agulha),
            'array' => in_array($agulha, $this->item_de_teste) || array_key_exists($agulha, $this->item_de_teste),
            'object' => (
                in_array($agulha, ($object = (new \ReflectionObject($this->item_de_teste)))->getAttributes())
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
    public function serIgual(mixed $item_de_comparacao)
    {
        $retorno = $this->item_de_teste === $item_de_comparacao;
        return $this->processarRetorno($retorno, "é igual a {$item_de_comparacao}.");
    }


    /**
     * Verifica se o item de teste existe
     * @author Brunoggdev
    */
    public function existir()
    {
        $retorno = isset($this->item_de_teste);
        return $this->processarRetorno($retorno, "existe.");
    }


    /**
     * Verifica se o item de teste é um arquivo válido
     * @author Brunoggdev
    */
    public function serArquivo()
    {
        $retorno = is_file($this->item_de_teste);
        return $this->processarRetorno($retorno, "é um arquivo válido.");
    }

    /**
     * Verifica se o item de teste é um diretório válido
     * @author Brunoggdev
    */
    public function serDiretorio()
    {
        $retorno = is_dir($this->item_de_teste);
        return $this->processarRetorno($retorno, "é um diretório válido.");
    }


    /**
     * Verifica se o item de teste é um diretório válido
     * @author Brunoggdev
    */
    public function serVazio()
    {
        $retorno = empty($this->item_de_teste);
        return $this->processarRetorno($retorno, "está vazio.");
    }
}
