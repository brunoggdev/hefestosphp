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
    extract($dados);
    return require pasta_app("Views/$view.php");
}



/**
* Retorna o conteúdo de um componente especificada
* @author Brunoggdev
*/
function componente(string $componente, ?array $dados = [])
{
    // Note que esta função não usa "return" pois já 
    // está implicito na função view()
    view("componentes/$componente", $dados);
}



/**
* Higieniza todos os campos de um array
* @author Brunoggdev
* @origem Common.php
*/
function higienizaArray(array $array):array
{
    // O "&" antes da variavel indica que estou alterando o array original
    // e não apenas uma cópia dele;
    foreach ($array as &$item) {
        if (is_array($item)) {
          $item = higienizaArray($item);
        } else {
          $item = strip_tags($item);
        }
      }
      return $array;
}



/**
* Retorna a criptografia da senha informada no padrão adotado pelo PHP
* @author Brunoggdev
* @origem Common.php
*/
function criptografarSenha(string $senha):string
{
    return password_hash($senha, PASSWORD_DEFAULT);
}

