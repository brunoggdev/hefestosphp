<?php

usar([
    'teste0' => null,
]);

testar('se a propriedade teste0 é um número e é float', function(){

    esperar($this->teste0)
        ->nao()->serNulo()
        ->serNumero()
        ->serFloat();

});


testar('se PASTA_RAIZ/public é um diretório válido', function(){
    return esperar(PASTA_RAIZ.'/public')->serDiretório();
});


testar('se pode retornar sua própria condicional pros testes', function(){
    return 1+1 === 2;
});


testar('se pode usar Exceptions normalmente (deve falhar!)', function(){
    throw new Exception("Devo ver esse errno no console ao testar!");
});