<?php
try {

    require '../system/Core/iniciar_app.php';
    require '../app/Config/rotas.php';

    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    $metodoHttp = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    
    // echo a resposta do método chamado
    echo $rota->mapear($uri, $metodoHttp);

    exit;

} catch (\Throwable $erro) {
    ob_clean(); // Clean the output buffer
    http_response_code(500); // Set the appropriate HTTP response code for the error
    
    die( view('debug', ['erro' => $erro]) );
}