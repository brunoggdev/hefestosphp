<?php
require PASTA_RAIZ . 'system/Testes/testes.php';


$testar->se('1 + 1 = 2', function(){
    return confereVerdadeiro(1 + 1 == 2);
});


$testar->se('1 + 1 = 3', function(){
    return confereVerdadeiro(1 + 1 == 3);
});
