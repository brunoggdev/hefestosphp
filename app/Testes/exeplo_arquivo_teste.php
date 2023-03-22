<?php
require_once PASTA_RAIZ . 'system/Testes/testes.php';


$testar->se('hello world tem só essas duas palavras', function(){
    return esperar('hello world')->nao()->conter('lucas');
});
