<?php

use Hefestos\Testes\Esperar;
use Hefestos\Testes\SuiteDeTestes;

/**
* Adiciona um novo teste na suíte de testes, recebendo uma descrição de até 75 caracteres pro teste e uma função que deve retornar um booleano;
* @param string $descricao Comentário a ser exibido no console durante os testes.
* @param callable $teste Função de teste que deve sempre retornar um bool (como as esperas).
* @author Brunoggdev
*/
function testar(string $descricao, callable $teste):void
{
    SuiteDeTestes::novoTeste($descricao, $teste);
}


/**
 * Adiciona propriedades na suite de testes a serem compartilhadas entre todos os testes
 * @param array $propiedades Um array associativo com o nome da propriedade e seu valor
 * @author Brunoggdev
*/
function usar(array $propriedades):void
{
    SuiteDeTestes::usarPropriedades($propriedades);
}

/**
* passe qualquer tipo de parametro à ser testado aqui 
* (String, array, objeto, condição, etc).
* Atalho para interair com a classe Esperar.
* @author Brunoggdev
*/
function esperar(mixed $item):Esperar
{
    return new Esperar($item);
}