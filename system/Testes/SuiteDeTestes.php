<?php

namespace Hefestos\Testes;


/**
* Guarda uma suíte de testes a serem executados pela CLI
* @author Brunoggdev
*/
class SuiteDeTestes
{
    private static ?self $instancia = null;
    protected static $testes = [];
    protected static $propriedades = [];

    /**
    * Adiciona um novo teste na suíte de testes
    * @param string $descricao Comentário até 75 caracteres a ser exibido no console durante os testes.
    * @param callable $teste Função de teste que deve sempre retornar um bool (como as esperas).
    * @author Brunoggdev
    */
    public static function novoTeste(string $descricao, callable $teste):void
    {
        static::$testes[] = [
            'descricao' => $descricao,
            'funcao' => $teste
        ];
    }


    /**
     * Adiciona propriedades na suite de testes a serem utilizadas em todos os testes
     * @param array|callable $propiedades Um array associativo com o nome da propriedade e seu valor ou uma função que retorne o array associativo desejado
     * @author Brunoggdev
    */
    public static function usarPropriedades(array|callable $propriedades):void
    {
        if (is_callable($propriedades)) {
            $propriedades = is_array($retorno = $propriedades()) ? $retorno : [];
        }
        
        foreach ($propriedades as $propriedade => $valor) {
            static::$propriedades[$propriedade] = $valor;
        }
    }

    
    /**
     * Retorna a suite de testes
     * @author Brunoggdev
    */
    public static function instancia():self
    {
        if (is_null(self::$instancia)) {
            self::$instancia = new self();
        }

        return self::$instancia;
    }
}
