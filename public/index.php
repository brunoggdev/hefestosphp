<?php
try {

    require '../system/app.php';

    // Instanciar roteador
    $rota = new \System\Roteador();
    require pasta_app('Config/rotas.php');

    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    $metodoRequisicao = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    

    $rota->mapear($uri, $metodoRequisicao);

    // echo a resposta do método chamado

} catch (\Throwable $th) {
    throw $th;
}