<?php

require '../app/Config/constantes.php';

// funções auxiliares do sistema nativas do framework
require BASE_PATH . 'system/auxiliares.php';

// funções auxiliares do usuário do framework
require BASE_PATH . 'app/auxiliares.php';

// Instanciar roteador

// echo a resposta do método chamado


function controller(){
    return view('home', ['saudacao' => 'Hello']);
}


echo controller();