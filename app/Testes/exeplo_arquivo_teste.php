<?php
require_once PASTA_RAIZ . 'system/Testes/testes.php';


$testar->se('o exemplo funciona', function(){
    return esperar(1+1 === 2)->serVerdadeiro();
});
