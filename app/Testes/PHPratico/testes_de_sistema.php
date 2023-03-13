<?php
require_once PASTA_RAIZ . 'system/Testes/testes.php';


$testar->se('um mais um engual a dois', function(){
    return esperar(1+1 === 2)->serVerdadeiro();
});

$testar->se('teste de exceção', function(){
    throw new \Exception('TEVE UM ERRO FODA AQUI PATRÃO');
    return esperar(1+1 === 2)->serFalso();
});
$testar->se('um mais um engual a dois de novo', function(){
    return esperar(1+1 === 2)->serVerdadeiro();
});

$testar->se('um mais um a dois é falso', function(){
    return esperar(1+1 === 2)->serFalso();
});

$testar->se('um mais um engual a dois é falso', function(){
    return esperar(1+1 === 2)->serFalso();
});

$testar->se('um engual a dois é falso', function(){
    return esperar('bruno' === 'bruno')->serVerdadeiro();
});
$testar->se('um mais um engual a dois é falso', function(){
    return esperar('bruno' === 'bruno')->serFalso();
});
