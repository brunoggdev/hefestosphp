<?php

usar([
    'teste0' => 100,
    'teste1' => "com uma string",
    'teste2' => ['com um array', 200],
]);

testar('se a propriedade teste0 é um número', function(){

    return esperar($this->teste0)->serNumero();

});

testar('se teste0 é um float', function(){

    return esperar($this->teste0)->serFloat();

});

testar('se teste0 é maior que 200', function(){

    $teste = ($this->teste0 > 200);
    return esperar($teste)->serVerdadeiro();

});

testar('se a propriedade teste2 contém 200', function(){

    return esperar($this->teste2)->conter('200');

});

testar('se PASTA_RAIZ/public é um diretório válido', function(){
    return esperar(PASTA_RAIZ.'/public')->serDiretório();
});
