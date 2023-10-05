<?php

use Hefestos\Core\Controller;
use Hefestos\Core\Model;
use Hefestos\Database\Database;
use Hefestos\Rotas\Redirecionar;
use Hefestos\Ferramentas\Colecao;
use Hefestos\Ferramentas\Requisicao;
use Hefestos\Ferramentas\Sessao;

/* ----------------------------------------------------------------------
Arquivo de funções auxiliares padrões do HefestosPHP.
Normalmente você não deve modificar este arquivo.
Caso queira adicionar suas próprias funções auxiliares, 
utilize o arquivo auxiliares.php encontrado na pasta app.
---------------------------------------------------------------------- */


/**
* Retorna o caminho da pasta app concatenado ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function pasta_raiz(string $caminho_extra = ''):string
{
    if(!str_starts_with($caminho_extra, '/')){
        $caminho_extra = '/' . $caminho_extra;
    }

    return PASTA_RAIZ . $caminho_extra;
}



/**
* Retorna o caminho da pasta app concatenado ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function pasta_app(string $caminho_extra = ''):string
{
    if(!str_starts_with($caminho_extra, '/')){
        $caminho_extra = '/' . $caminho_extra;
    }

    return PASTA_RAIZ . 'app' . $caminho_extra;
}



/**
* Retorna o caminho da pasta public concatenado ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function pasta_public(string $caminho_extra = ''):string
{
    if(!str_starts_with($caminho_extra, '/')){
        $caminho_extra = '/' . $caminho_extra;
    }

    return PASTA_RAIZ . 'public' . $caminho_extra;
}



/**
* Retorna a url base do app concatenada ou não 
* com um parametero opcional de caminho adicional
* @author Brunoggdev
*/
function url_base(string $caminho_extra = ''):string
{
    $url_completa = rtrim(URL_BASE, '/') . '/' . ltrim($caminho_extra, '/');

    if (!str_starts_with($url_completa, 'http')) {

        $prefixo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://';
        $url_completa = $prefixo . $url_completa;
    }

    return $url_completa;
}




/**
* "Die and Dump"
*
* Interrompe a execução do app onde quer que for chamada e
* imprime o que for passado como parametro opcional para debug.
* @author Brunoggdev
*/
function dd(mixed ...$params)
{
    $terminal =  http_response_code() === false;

    if(!$terminal){
        echo '<pre>';
    }

    echo  $terminal ? PHP_EOL : '<br>';
    
    foreach ($params as $param) {
        var_dump($param);
        echo  $terminal ? PHP_EOL.PHP_EOL : '<br><hr><br>';
    }

    exit;
}



/**
 * Atalho conveniente para retornar uma instancia do controller desejada
 * @author Brunoggdev
*/
function controller(string $controller):Controller
{
    $controller = "\\App\\Controllers\\$controller";
    return new $controller;
}



/**
 * Atalho conveniente para retornar uma instancia da model desejada
 * @author Brunoggdev
*/
function model(string $model):Model
{
    $model = "\\App\\Models\\$model";
    return new $model;
}



/**
* Retorna o conteúdo da view especificada, podendo receber um array de dados a serem utilizados nela
* @author Brunoggdev
*/
function view(string $nome_view, ?array $dados = []):string
{
    // toraando o array de dados em variaveis disponíveis na view;
    extract($dados);

    // Guarda o conteúdo da view requerida como string
    ob_start();

    // Busca pela view do usuário ou do sistema respectivamente
    $view = "Views/$nome_view.php";
    match (true) {
        is_file($arquivo = PASTA_RAIZ . "app/$view") => require $arquivo,
        is_file($arquivo = PASTA_RAIZ . "system/$view") => require $arquivo,
        default => throw new Exception("View '$nome_view' não encontrada.", 69)
    };

    // Retornando o conteúdo da view que foi guardado como string
    return ob_get_clean();
}




/**
* Retorna o array ou objeto informado como JSON
* @author Brunoggdev
*/
function json(mixed $param):string
{
    return json_encode($param, JSON_PRETTY_PRINT);
}



/**
 * Retorna uma string com o conteúdo de um componente especificado se existir ou vazia caso contrário.
 * Também receber um array associativo de dados a serem utilizados.
 * @author Brunoggdev
*/
function componente(string $nome_componente, ?array $dados = []):string
{
    $componente = "/componentes/$nome_componente";
    $componente_existe = file_exists(pasta_app("Views/$componente.php"));

    return $componente_existe ? view($componente, $dados) : '';
}


/**
 * Retorna uma string com o conteúdo de um componente especificado ou exceção se não existir.
 * Também receber um array associativo de dados a serem utilizados. 
 * @author Brunoggdev
*/
function comp(string $nome_componente, ?array $dados = []):string
{
    $componente = "/componentes/$nome_componente";

    if (!file_exists(pasta_app("Views/$componente.php"))) {
        throw new Exception("Componente '$nome_componente' não encontrado.", 70);
    }

    return view($componente, $dados);
}

/**
 * Retorna a string para importação do arquivo JavaScript na pasta publica 'js/' 
 * com o nome informado (se existir), podendo "defer".
 * @author Brunoggdev
*/
function importarJS(string $nome_arquivo, bool $defer = false):string
{
    $arquivo = "js/$nome_arquivo.js";

    if (!file_exists(pasta_public($arquivo))) {
        return '';
    }
    
    return '<script '.($defer?'defer ':'').' src="'.url_base("$arquivo?v=").VERSAO_APP.'"></script>';
}


/**
 * Define o código http desejado e para a execução; Opcionalmente
 * envia também uma string de resposta (pode ser uma view);
 * @author Brunoggdev
*/
function abortar(int $codigo_http, string $retorno = ''):void
{
    ob_clean(); // limpa o buffer de saída
    http_response_code($codigo_http);

    die($retorno);
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
* Retorna uma instancia da Sessao ou pega um 
* elemento da sessão caso alguma chave seja passada
* (retorna null se não houver para a chave indormada).
* @return Sessao 
* @author Brunoggdev
*/
function sessao(string|false $chave = false):mixed
{
    $sessao = new Sessao();

    return $chave ? $sessao->pegar($chave) : $sessao;
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
function encriptar(string $senha):string
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
function metodoHttp(string $metodo_http):string
{
    $metodo_http = strtoupper($metodo_http);
    return "<input type='hidden' name='_method' value=$metodo_http>";
}


/**
* Abre uma tag form e configura os atributos action e method
* @author Brunoggdev
*/
function abreForm(string $metodo_http, string $action):string
{
    $metodo_http = strtoupper($metodo_http);
    $action = str_starts_with($action, 'http') ? $action : url_base($action);
    
    if($metodo_http === 'GET' || $metodo_http === 'POST'){
        $retorno = "<form action=$action method=$metodo_http>";
    }else{
        $retorno = "<form action=$action method=POST>";
        $retorno .= "\n" . metodoHttp($metodo_http);
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

/**
 * Atalho conveniente para retornar uma instancia da Database;
 * Pode receber um array de configuração customizado.
 * @author Brunoggdev
*/
function db(?array $config = null):Database
{
    if (is_null($config)){
        return Database::singleton();
    }
    
    return new Database($config);
}


/**
 * Retorna a SQL da tabela com o nome desejado (sem a data de criação)
 * @author Brunoggdev
*/
function tabela(string $tabela)
{
    $tabelas = array_values(array_filter(
        scandir(pasta_app('Database/tabelas')),
        fn($item) =>  str_ends_with($item, "$tabela.php")
    ));

    if (empty($tabelas)) {
        throw new Exception("Nenhuma tabela encontrada com o nome $tabela.", 69);
    }

    return (string) require pasta_app("Database/tabelas/$tabelas[0]");
}