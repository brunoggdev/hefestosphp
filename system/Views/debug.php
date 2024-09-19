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
    if (AMBIENTE === 'producao'){
        echo '<h1>Ops, tivemos um problema.</h1>';
    }else{
        $trace = $erro->getTrace()[$erro->getCode()] ?? $erro->getTrace()[0];
        echo '<br>';
        echo '<h1>HefestosPHP</h1>';
        echo '<h3>Encontramos um erro.</h3>';
        echo '<br>';
        echo '<br>';
        echo '<strong>ERRO:</strong> ' . $erro->getMessage();
        echo '<br>';
        echo '<br>';
        echo '<strong>NA LINHA:</strong> ' . $trace['line'] ??= 'Não especificada';
        echo '<br>';
        echo '<br>';
        echo '<strong>DO ARQUIVO:</strong> ' . $trace['file'] ??= 'Não especificado';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<strong>TRILHA:</strong>'; ;
        echo '<br>';
        echo '<pre>';
        foreach ($erro->getTrace() as $i => $traco) {
            echo '<div style="background-color: '. ($i % 2 == 0 ? '#f0f0f0' : '#d7d7d7') .'; padding: 15px; font-size: large">';
            echo '<strong>NA LINHA:</strong> ' . $traco['line'] ??= 'Não especificada';
            echo '<br>';
            echo '<strong>DO ARQUIVO:</strong> ' . $traco['file'] ??= 'Não especificado';
            echo '</div>';
        }
    }
    ?>
</body>
</html>
