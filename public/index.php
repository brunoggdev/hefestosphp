<?php
try {

    require '../system/app.php';

    // Instanciar roteador
    $rota = new \System\Roteador();
    require pasta_app('Config/rotas.php');

    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    $metodoRequisicao = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    

    echo $rota->mapear($uri, $metodoRequisicao);

    // echo a resposta do método chamado

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