<?php
/* ----------------------------------------------------------------------
Abaixo estão vários exemplos de uso dos testes que podem ser explorados.
---------------------------------------------------------------------- */

// testar('se true é verdadeiro', function(){
//     esperar(true)->serVerdadeiro();
// });


// // O teste irá falhar com o erro informado na função falhar(), sendo útil em condições ou try-catch's
// testar('a função falhar() com erro customizado (deve falhar)', function() {
//     $algo_deu_errado = true;

//     if ($algo_deu_errado) {
//         falhar('Algo deu errado! Essa mensagem de erro será mostrada no terminal.');
//     }
// });


// // O teste irá falhar pois o PHP não pode acessar o índice 7 já que ele não existe.
// testar('se posso acessar o índice 7 em um array com apenas 4 itens.', function() {
//     $array = [0, 1, 2, 3];

//     $setimo_item = $array[7];
// });


// testar('se o db de testes funciona e posso até mesmo usar models', function(){
//     $resultado = model('ExemploModel')->todos();

//     esperar($resultado)
//         ->serArray()
//         ->serVazio();
// });


// usar(function() {
//     $req = requisicao_get('http://jsonplaceholder.typicode.com/todos/1');

//     return [
//         'requisicao' => $req,
//     ];
// });


// testar('se a requisicao recebeu uma resposta com sucesso', function() {
//     $status_http = $this->requisicao->status();

//     esperar($status_http)->serIgual(200);
// });


// testar('se a resposta da requisicao contem a chave title', function() {
//     $resposta = $this->requisicao->resposta('array');

//     esperar($resposta)
//         ->serArray()
//         ->conter('title');
// });


// // Qualquer função pode ser informada aqui, arrow functions não são exceção.
// usar(function() {
//     return [
//         'valor_teste' => 100
//     ];
// });


// testar("se valor_teste não é nulo, e é um número e é inteiro", function() {
//     esperar($this->valor_teste)
//         ->nao()->serNulo()
//         ->serNumero()
//         ->serInteiro();
// });
  

// // Claro, como pode usar a função para vários valores no mesmo teste, pode também para o mesmo valor se preferir 
// testar("se valor_teste não é nulo, e é um número e é float", function() {
//     esperar($this->valor_teste)->nao()->serNulo();
//     esperar($this->valor_teste)->serNumero();
//     esperar($this->valor_teste)->serFloat();
// });