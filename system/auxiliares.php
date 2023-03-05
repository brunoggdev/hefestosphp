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
function pasta_app(?string $caminhoExtra = ''):string
{
    return BASE_PATH . "app/$caminhoExtra";
}



/**
* Retorna o caminho da pasta public concatenado ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function pasta_public(?string $caminhoExtra = ''):string
{
    return BASE_PATH . "public/$caminhoExtra";
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
* Higieniza o parametro informado.
* Se for um array, todos os campos serão higienizados.
* @author Brunoggdev
*/
function higienizar(string|array $param):string|array
{

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

