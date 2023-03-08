<?php
# ----------------------------------------------------------------------
# Arquivo de funções auxiliares padrões do PHPratico.
# Normalmente você não deve modificar este arquivo.
# Caso queira adicionar suas próprias funções auxiliares, 
# utilize o arquivo auxiliares.php encontrado na pasta app.
# ----------------------------------------------------------------------

/**
* Retorna o caminho da pasta app concatenado ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function pasta_raiz(?string $caminhoExtra = ''):string
{
    return PASTA_RAIZ . $caminhoExtra;
}



/**
* Retorna o caminho da pasta app concatenado ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function pasta_app(?string $caminhoExtra = ''):string
{
    return PASTA_RAIZ . "app/$caminhoExtra";
}



/**
* Retorna o caminho da pasta public concatenado ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function pasta_public(?string $caminhoExtra = ''):string
{
    return PASTA_RAIZ . "public/$caminhoExtra";
}



/**
* Retorna a url base do app concatenada ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function url_base(?string $caminhoExtra = ''):string
{
    return URL_BASE . "public/$caminhoExtra";
}




/**
* "Die and Dump"
*
* Interrompe a execução do app onde quer que for chamada e
* imprime o que for passado como parametro opcional para debug.
* @author Brunoggdev
*/
function dd(mixed $param = null)
{
    if ($param) {
        echo '<pre>';
        var_dump($param);
    }
    exit;
}



/**
* Retorna o conteúdo da view especificada
* @author Brunoggdev
*/
function view(string $view, ?array $dados = []):string
{
    // Transforma um array associativo em variaveis.
    extract($dados);

    // Guarda o conteúdo da view requerida como string
    ob_start();

    require pasta_app("Views/$view.php");

    // Retornando o conteúdo da view que foi guardado como string
    return ob_get_clean();
}



/**
* Retorna o conteúdo de um componente especificado como string
* @author Brunoggdev
*/
function componente(string $componente, ?array $dados = []):string
{
    return view("componentes/$componente", $dados);
}



/**
* Define o código http de resposta e retorna a 
* view do codigo desejado (404 por padrão);
* @author Brunoggdev
*/
function abortar(?int $codigo = 404):string
{
    http_response_code($codigo);
    return view("$codigo");
}


/**
* Redireciona o usuario para a rota informada, 
* além de atualizar o código http (302 por padrão).
* @author Brunoggdev
*/
function redirecionar(string $url, int $codigo = 302)
{
    http_response_code($codigo);
    header("Location: $url");
    exit;
}


/**
* Retorna o array ou objeto informado como JSON
* @author Brunoggdev
*/
function json(array|object $param):string
{
    return json_encode($param);
}



/**
* Higieniza o parametro informado.
* Se for um array, todos os campos serão higienizados.
* @author Brunoggdev
*/
function higienizar(null|string|array $param):null|string|array
{

    if( is_null($param) ){
        return null;
    }

    
    if( is_string($param) ){
        return strip_tags($param);
    }

    // O "&" antes da variavel indica que estou alterando o 
    // item em si e não apenas uma cópia dele;
    foreach ($param as &$item) {
        if (is_array($item)) {
          $item = higienizar($item);
        } else {
          $item = strip_tags($item);
        }
    }

    return $param;
}



/**
* Retorna a criptografia da senha informada no padrão adotado pelo PHP
* @author Brunoggdev
*/
function protegerSenha(string $senha):string
{
    return password_hash($senha, PASSWORD_DEFAULT);
}

