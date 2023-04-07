<?php
try {

    require '../system/core/iniciar_app.php';
    require '../app/Config/rotas.php';

    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    $metodoHttp = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    
    // echo a resposta do método chamado
    echo $rota->mapear($uri, $metodoHttp);

    exit;

} catch (\Throwable $erro) {

    if (ENVIROMENT === 'producao'){

        echo '<h1>Ops, tivemos um problema.</h1>';

    }else{
        echo '<br>';
        echo '<h1>HefestosPHP</h1>';
        echo '<h3>Encontramos um erro.</h3>';
        echo '<br>';
        echo '<br>';
        echo '<strong>ERRO:</strong> ' . $erro->getMessage() . '.';
        echo '<br>';
        echo '<br>';
        echo '<strong>NA LINHA:</strong> ' . $erro->getLine();
        echo '<br>';
        echo '<br>';
        echo '<strong>DO ARQUIVO:</strong> ' . $erro->getFile();
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<strong>TRILHA:</strong>'; ;
        echo '<br>';
        echo '<pre>';
        foreach ($erro->getTrace() as $traco) {
            print_r($traco);
        }
        exit;
    }
}