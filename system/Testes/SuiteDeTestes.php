<?php

namespace Hefestos\Testes;


/**
* Guarda uma suíte de testes a serem executados pela CLI
* @author Brunoggdev
*/
class SuiteDeTestes
{
    protected $testes = [];

    /**
    * Adiciona um novo teste na suíte de testes
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
     * Adiciona propriedades na suite de testes a serem utilizadas em todos os testes
     * @param array $propiedades Um array associativo com o nome da propriedade e seu valor
     * @author Brunoggdev
    */
    public function com(array $propriedades):void
    {
        foreach ($propriedades as $propriedade => $valor) {
            $this->$propriedade = $valor;
        }
    }
}
