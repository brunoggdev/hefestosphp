<?php

use Hefestos\Testes\Esperar;
use Hefestos\Testes\SuiteDeTestes;

/**
 * Adiciona um novo teste na suíte de testes, recebendo uma descrição para o teste e uma função com o teste de fato;
 * @param string $descricao Comentário a ser exibido no console durante os testes.
 * @param callable $teste Função de teste que deve sempre utilizar a função esperar, retornar um booleano ou lançar exceções.
 * @author Brunoggdev
*/
function testar(string $descricao, callable $teste):void
{
    SuiteDeTestes::novoTeste($descricao, $teste);
}


/**
 * Executa a função informada; Se seu retorno for um array associativo, 
 * adiciona propriedades na suite de testes a serem compartilhadas entre todos os testes 
 * subsequentes por meio do $this;
 * @author Brunoggdev
*/
function usar(callable $funcao):void
{
    SuiteDeTestes::usar($funcao);
}

/**
 * Atalho para interair com a classe Esperar;
 * Passe qualquer tipo de parametro à ser testado aqui 
 * (String, array, objeto, condição, etc);
 * Lança exceções em casos negativos. 
 * @throws Exception
 * @author Brunoggdev
*/
function esperar(mixed $item):Esperar
{
    return new Esperar($item);
}


/**
 * Causa uma falha no teste e recebe uma descrição customizada para ela.
 * @author Brunoggdev
*/
function falhar(string $descricao):void
{
    throw new Exception($descricao);
}