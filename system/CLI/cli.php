<?php

match ($argv[1]) {
    'serve', 'servir', 'iniciar' => serve($argv[2] ?? '8080'),
    'fazer' => fazer( ucfirst($argv[2]), $argv[3] ?? '')
};
    

/**
* Inicia um servidor embutido do PHP para a pasta public 
* na porta desejada (padrão 8080)
* @author Brunoggdev
*/
function serve($porta)
{
    exec("php -S localhost:$porta -t public");
}



/**
* Cria um novo arquivo com as propriedades desejadas
* @author Brunoggdev
*/
function fazer($arquivo, $nome)
{

    if(empty($nome)){
        echo "\n # Você deve informar um nome pro arquivo depois do tipo.\n";
        echo "\n # Ex.: php pratico fazer $arquivo Usuarios$arquivo.\n\n";
        exit;
    }


    require 'app/Config/constantes.php';

    $caminho = match ($arquivo) {
        'Controller' =>  BASE_PATH . 'app/Controllers/',
        'Model' => BASE_PATH . 'app/Models/',
    };

    $base = $arquivo . 'Base';
    $namespace = 'App\\' . $arquivo . 's';
    
    $template = <<<EOT
    <?php

    namespace $namespace;

    class $nome extends $base
    {
        // Seus metodos de controller aqui
    }

    EOT;

    // Replace the template placeholders with actual values
    // $template = str_replace('{{class_name}}', $nomeDoArquivo, $template);

    if ( file_put_contents("$caminho$nome.php", $template) ) {
        echo "$arquivo criado sucesso.\n";
    } else {
        echo "Algo deu errado ao gerar o $arquivo.\n";
    }
}