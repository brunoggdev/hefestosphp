<?php
# ----------------------------------------------------------------------
# Arquivo de funções auxiliares padrões do PHPratico.
# Normalmente você não deve modificar este arquivo.
# Caso queira adicionar suas próprias funções auxiliares, 
# utilize o arquivo auxiliares.php encontrado na pasta app.
# ----------------------------------------------------------------------

use System\Core\Classes\Colecao;
use System\Core\Classes\Redirecionar;
use System\Core\Classes\Requisicao;
use System\Core\Classes\Session;

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
* Retorna o array ou objeto informado como JSON
* @author Brunoggdev
*/
function json(array|object $param):string
{
    return json_encode($param);
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
function redirecionar(string $url = '', int $codigo = 302):Redirecionar
{
    $redirecionar = new Redirecionar();

    if(! empty($url) ){
        $redirecionar->para($url);
    }

    return $redirecionar;
}


/**
* Retorna uma instancia da Session ou pega um 
* elemento da sessão caso alguma chave seja passada
* (retorna null se não houver para a chave indormada).
* @return Session 
* @author Brunoggdev
*/
function sessao(string|false $chave = false):mixed
{
    $session = new Session();

    return $chave ? $session->pegar($chave) : $session;
}



/**
* Atalho para interagir com a classe de Requisicao para 
* realizar uma requisicao get simples; 
* Se precisar de mais controle instancie a classe manualmente
* @author Brunoggdev
*/
function requisicaoGet(string $endpoint):Requisicao
{
    return (new Requisicao())->get($endpoint);
}


/**
* Atalho para interagir com a classe de Requisicao para 
* realizar uma requisicao post simples; 
* Se precisar de mais controle instancie a classe manualmente
* @author Brunoggdev
*/
function requisicaoPost(string $endpoint, array $dados):Requisicao
{
    return (new Requisicao())->post($endpoint, $dados);
}


/**
* Higieniza o parametro informado.
* Se for um array, todos os campos serão higienizados de forma recursiva.
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



/**
* Checa se a url atual corresponde à informada
* @author Brunoggdev
*/
function url_igual(string $url):bool
{
    return $_SERVER['REQUEST_URI'] === $url;
}


/**
* Checa se a url atual contém a parte informada
* @author Brunoggdev
*/
function url_contem(string $parte):bool
{
    return str_contains($_SERVER['REQUEST_URI'], $parte);
}


/**
* Adiciona um input hidden para especificar o tipo de requisicao desejado
* @author Brunoggdev
*/
function metodoHttp(string $metodoHttp):string
{
    $metodoHttp = strtoupper($metodoHttp);
    return "<input type='hidden' name='_method' value=$metodoHttp>";
}


/**
* Abre uma tag form e configura os atributos action e method
* @author Brunoggdev
*/
function abreForm(string $metodoHttp, string $action):string
{
    $metodoHttp = strtoupper($metodoHttp);

    if($metodoHttp === 'GET' || $metodoHttp === 'POST'){
        $retorno = "<form action=$action method=$metodoHttp>";
    }else{
        $retorno = "<form action=$action method=POST>";
        $retorno .= "\n" . metodoHttp($metodoHttp);
    }

    return $retorno;
}


/**
* Fecha a tag form
* @author Brunoggdev
*/
function fechaForm():string
{
    return '</form>';
}


/**
* Atalho para interagir com a classe Colecao
* @author Brunoggdev
*/
function coletar(array $array):Colecao
{
    return new Colecao($array);
}