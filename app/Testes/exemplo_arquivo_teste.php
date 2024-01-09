<?php

testar('se true é verdadeiro', function(){
    esperar(false)->serVerdadeiro();
});


/* ----------------------------------------------------------------------
Abaixo estão vários exemplos de uso comentados que podem ser explorados.
---------------------------------------------------------------------- */

// usar([
//     'teste' => 100,
//     'outro_teste' => null
// ]);

// usar(function() {
//     $res = requisicaoGet('http://jsonplaceholder.typicode.com/todos/1')->resposta('array');

//     return [
//         'teste_funcao' => $res
//     ];
// });

// testar('se a propriedade teste é um número e é float', function(){

//     esperar($this->teste)
//         ->nao()->serNulo()
//         ->serNumero()
//         ->serFloat();

// });


// testar('se PASTA_RAIZ/public é um diretório válido', function(){
//     return esperar(PASTA_RAIZ.'/public')->serDiretório();
// });


// testar('se pode retornar sua própria condicional pros testes', function(){
//     return 1+1 === 2;
// });


// testar('se pode usar Exceptions normalmente (deve falhar!)', function(){
//     throw new Exception("Devo ver esse errno no console ao testar!");
// });

// testar('se posso usar funcoes para retornar o array em usar()', function(){
//     esperar($this->teste_funcao)
//         ->nao()->serNulo()
//         ->serArray()
//         ->conter('title');
// });