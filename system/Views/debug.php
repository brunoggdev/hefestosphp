<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HefestosPHP - Erro</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap');
        body{
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body>
    <?php
    if (ENVIROMENT === 'producao'){
        echo '<h1>Ops, tivemos um problema.</h1>';
    }else{
        echo '<br>';
        echo '<h1>HefestosPHP</h1>';
        echo '<h3>Encontramos um erro.</h3>';
        echo '<br>';
        echo '<br>';
        echo '<strong>ERRO:</strong> ' . $erro->getMessage();
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
    }
    ?>
</body>
</html>