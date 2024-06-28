/// <reference path="jquery.min.js" />
/// <reference path="helpers_hefestos.js" />
'use strict'; 
/**linhas auxiliares acima, evite apagar. @author Brunoggdev*/

onClick('#alertar', function() {
    alertar('Você clicou com sucesso, parabéns!', function() {
        alertar('Sério, isso significa que você conseguiu capturar um evento de click :]')
    })
})


onClick('#confirmar', function() {
    confirmar("Tem certeza disso?", 
        () => { alertar('Você confirmou :]')},
        () => { alertar('Você cancelou >:[') }
    )
})


onClick('#ajax', function() {
    confirmar('Deseja fazer uma requisição AJAX para o back-end?', function() {
        requisicaoGet('outra-demo', resposta => {
            alertar(`<p class="text-${resposta.cor}" >${resposta.texto}</p>`)
        })
    })
})


onClick('#toast', function() {
    const datetime = new Date()
    novo_toast(`são ${datetime.getHours()}:${datetime.getMinutes()}:${datetime.getSeconds()}`)
})