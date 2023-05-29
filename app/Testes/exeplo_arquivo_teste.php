<?php
require_once PASTA_RAIZ . 'system/Testes/testes.php';

$testar->com(['teste' => "funciona memo meu parsero"]);

$testar->se('a funcionalidade "com" da suite de testes funciona', function(){
    echo $this->teste;
    return esperar($this->teste)->serIgual('funciona memo meu parsero');
});

$testar->se('hello world tem só essas duas palavras', function(){
    return esperar('hello world')->nao()->conter('lucas');
});
