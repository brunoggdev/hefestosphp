<?php
try {

    require '../system/core/app.php';

    require pasta_app('Config/rotas.php');

    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    $requisicao = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    

    // echo a resposta do método chamado
    echo $rota->mapear($uri, $requisicao);


} catch (\Throwable $th) {

    if (ENVIROMENT === 'desenvolvimento'){
        echo '<br>';
        echo '<h1>PHPratico</h1>';
        echo 'Encontramos um erro. Aqui está o que o php diz sobre isso:';
        echo '<br>';
        echo '<br>';
        throw $th;
    }

    echo '<h1>Opa, tivemos um problema.</h1>';
}