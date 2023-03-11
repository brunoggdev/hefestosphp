<?php
require PASTA_RAIZ . 'system/Testes/testes.php';


$testar->se('1 + 1 = 2', function(){
    return confereVerdadeiro(1 + 1 == 2);
});


$testar->se('1 + 1 = 3', function(){
    return confereVerdadeiro(1 + 1 == 3);
});

$testar->se('esperar ser functiona', function(){
    return esperar(['Hello world!'])->serObjeto();
});
