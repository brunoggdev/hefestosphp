<?php

namespace Hefestos\Ferramentas;

/**
* Encapsula um array para ser tratado como objeto.
* Chaves do array encapsulado podem ser acessadas como propriedades.
* @author Brunoggdev
* @return array array encapsulado
*/
class Colecao
{
    
    public function __construct(private array $colecao)
    {
    }

    public function __get(string|int $propriedade)
    {
        return $this->colecao[$propriedade] ?? null;
    }

    // public function __set($name, $value)
    // {
    //     $this->colecao[$name] = $value;
    // }
    
    public function __toString()
    {
        return json_encode($this->colecao);
    }

    /**
    * Retorna o array encapsulado
    * @author Brunoggdev
    */
    public function array():array
    {
        return $this->colecao;
    }

    /**
    * Executa a função informada para cada item do array comum com um único parametro.
    * Caso queira interromper a execução em algum momento basta retornar false na função.
    * @author Brunoggdev
    */
    public function each(callable $callback)
    {
        foreach ($this->colecao as $item) {
            if( $callback($item) === false ){
                break;
            }
        }
        
        return $this;
    }


    /**
    * Executa a função informada para cada item do array associativo com chave e valor como parametros.
    * Caso queira interromper a execução em algum momento basta retornar false na função.
    * @author Brunoggdev
    */
    public function eachAssoc(callable $callback)
    {
        foreach ($this->colecao as $key => $value) {
            if( $callback($key, $value) === false ){
                break;
            }
        }
        
        return $this;
    }


    /**
    * Executa a função informada para cada item do array e devolve uma coleção com o array resultante.
    * @author Brunoggdev
    */
    public function map(callable $callback):Colecao
    {
        return new Colecao( array_map($callback, $this->colecao) );
    }


    /**
    * Devolve uma colecao com todos os valores do array
    * @author Brunoggdev
    */
    public function valores():Colecao
    {
        return new Colecao( array_values($this->colecao) );
    }


    /**
    * Devolve uma colecao com todas as chaves do array
    * @author Brunoggdev
    */
    public function chaves():Colecao
    {
        return new Colecao( array_keys($this->colecao) );
    }


    /**
    * Devolve uma colecao com a diferenca entre o array original e o informado
    * @author Brunoggdev
    */
    public function diferenca(array $array):Colecao
    {
        return new Colecao( array_diff($array) ); 
    }


    /**
    * Devolve o valor do ultimo item do array.
    * @author Brunoggdev
    */
    public function primeiro()
    {
        $retorno = reset($this->colecao);
        
        return is_array($retorno) ? new Colecao($retorno) : $retorno;
    }


    /**
    * Devolve o valor do ultimo item do array.
    * @author Brunoggdev
    */
    public function ultimo()
    {
        $retorno = end($this->colecao);

        return is_array($retorno) ? new Colecao($retorno) : $retorno;
    }


    /**
    * Devolve a chave do primeiro item do array.
    * @author Brunoggdev
    */
    public function primeiraChave()
    {
        $retorno = array_key_first($this->colecao);

        return is_array($retorno) ? new Colecao($retorno) : $retorno;
    }


    /**
    * Devolve a chave do ultimo item do array.
    * @author Brunoggdev
    */
    public function ultimaChave()
    {
        $retorno = array_key_last($this->colecao);

        return is_array($retorno) ? new Colecao($retorno) : $retorno;
    }


    /**
    *  Devolve a soma dos itens do array.
    * @author Brunoggdev
    */
    public function soma(string|int $chave = '')
    {
        if ($chave === '') {
            return array_sum($this->colecao);
        }
        
        return array_sum(array_column($this->colecao, $chave));
    }


    /**
     * Verifica se o item desejado existe no array
     * @author Brunoggdev
    */
    public function contem(mixed $item):bool
    {
        return in_array($item, $this->colecao);
    }


    /**
     * Verifica se uma determinada chave desejado existe no array
     * @author Brunoggdev
    */
    public function contemChave(mixed $cahve):bool
    {
        return in_array($cahve, array_keys($this->colecao));
    }


    // ======== Modificam o array ===========


    /**
    *  Ordena o array.
    * @author Brunoggdev
    */
    public function ordenar()
    {
        sort($this->colecao);

        return $this;
    }

    /**
    *  Reverte a ordem do array.
    * @author Brunoggdev
    */
    public function reverso()
    {
        $this->colecao = array_reverse($this->colecao);
                
        return $this;
    }


    /**
    * Adiciona um novo elemento no array, seja ele um array associativo ou nao.
    * @author Brunoggdev
    */
    public function incluir(mixed $item)
    {
        if( is_array($item) ){
            $this->colecao = array_merge($this->colecao, $item);
        }else{
            array_push($this->colecao, $item);
        }

        return $this;
    }


    /**
    * Higieniza todos os itens do array de modo recursivo.
    * @author Brunoggdev
    */
    public function higienizar()
    {
        $this->colecao = higienizar($this->colecao);

        return $this;
    }

}