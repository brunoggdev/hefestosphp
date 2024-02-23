<?php

/* ----------------------------------------------------------------------
Abaixo estão vários exemplos de uso dos testes que podem ser explorados.
---------------------------------------------------------------------- */

// testar('se true é verdadeiro', function(){
//     esperar(true)->serVerdadeiro();
// });


// testar('se o db de testes funciona e posso até mesmo usar models', function(){
//     $resultado = model('ExemploModel')->buscarTodos();

//     esperar($resultado)
//         ->serArray()
//         ->serVazio();
// });
 

// usar(fn() => ['teste' => 100]);

// usar(function() {
//     $req = requisicaoGet('http://jsonplaceholder.typicode.com/todos/1');
    
//     return [
//         'requisicao' => $req,
//         'resposta_req' => $req->resposta('array')
//     ];
// });


// testar('se a propriedade teste é um número e é float', function(){

//     esperar($this->teste)
//         ->nao()->serNulo()
//         ->serNumero()
//         ->serFloat();

// });


// testar('se posso passar funcoes em usar() para executar código e retornar um array de propriedades', function(){
    
//     esperar($this->resposta_req)
//         ->nao()->serNulo()
//         ->serArray()
//         ->conter('title');
// });


// testar('se PASTA_RAIZ/public é um diretório válido', function(){
//     return esperar(PASTA_RAIZ.'/public')->serDiretorio();
// });


// testar('se a rota padrão retorna status 200', function(){
//     $req = requisicaoGet(url_base());

//     try {
//         esperar($req->status())->serIgual(200);
//     } catch (\Throwable) {
//         falhar($req->resposta());
//     }
    
// });


// testar('a função falhar() com erro customizado (deve falhar)', function(){
//     falhar('Devo ver essa mensagem no terminal ao testar.');
// });