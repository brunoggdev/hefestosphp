<?php

use Hefestos\Core\Controller;
use Hefestos\Core\Entidade;
use Hefestos\Core\Model;
use Hefestos\Database\Database;
use Hefestos\Database\Tabela;
use Hefestos\Ferramentas\ClienteHttp;
use Hefestos\Rotas\Redirecionar;
use Hefestos\Ferramentas\Colecao;
use Hefestos\Ferramentas\Sessao;
use Hefestos\Rotas\Requisicao;

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
function pasta_raiz(string $caminho_extra = ''): string
{
    if (!str_starts_with($caminho_extra, '/')) {
        $caminho_extra = '/' . $caminho_extra;
    }

    return PASTA_RAIZ . $caminho_extra;
}



/**
 * Retorna o caminho da pasta app concatenado ou não 
 * com um parametero opcional de caminho adicional
 * @author Brunoggdev
 */
function pasta_app(string $caminho_extra = ''): string
{
    if (!str_starts_with($caminho_extra, '/')) {
        $caminho_extra = '/' . $caminho_extra;
    }

    return PASTA_RAIZ . 'app' . $caminho_extra;
}



/**
 * Retorna o caminho da pasta public concatenado ou não 
 * com um parametero opcional de caminho adicional
 * @author Brunoggdev
 */
function pasta_public(string $caminho_extra = ''): string
{
    if (!str_starts_with($caminho_extra, '/')) {
        $caminho_extra = '/' . $caminho_extra;
    }

    return PASTA_PUBLIC . $caminho_extra;
}



/**
 * Retorna a url base do app concatenada ou não 
 * com um parametero opcional de caminho adicional
 * @author Brunoggdev
 */
function url_base(string $caminho_extra = ''): string
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
    // limpa o buffer de saída se estiver ativo
    if (ob_get_status()) {
        ob_clean();
    }
    
    $terminal =  http_response_code() === false;

    if (!$terminal) {
        echo '<html><pre>';
    }

    echo  $terminal ? PHP_EOL : '<br>';

    foreach ($params as $param) {
        if (is_string($param)) {
            $param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
        }
        var_dump($param);
        echo  $terminal ? PHP_EOL . PHP_EOL : '<br><hr><br>';
    }

    exit;
}



/**
 * Atalho conveniente para retornar uma instancia do controller desejada
 * @author Brunoggdev
 */
function controller(string $controller): Controller
{
    $controller = "\\App\\Controllers\\$controller";
    return new $controller;
}


/**
 * Atalho conveniente para retornar uma instancia do controller desejada
 * @author Brunoggdev
 */
function entidade(string $entidade, mixed $dados = null): Entidade
{
    $entidade = "\\App\\Entidades\\$entidade";
    return new $entidade($dados);
}



/**
 * Atalho conveniente para retornar uma instancia da model desejada
 * @author Brunoggdev
 */
function model(string $model, ?Database $db = null): Model
{
    $model = "\\App\\Models\\$model";
    return new $model($db);
}



/**
 * Retorna o conteúdo da view especificada, podendo receber um array de dados a serem utilizados nela
 * @author Brunoggdev
 */
function view(string $nome_view, ?array $dados = []): string
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
        default => throw new Exception("View '$nome_view' não encontrada.")
    };

    // Retornando o conteúdo da view que foi guardado como string
    return ob_get_clean();
}




/**
 * Usada para retornar uma resposta em formato JSON;
 * Retorna o array ou objeto informado para JSON, 
 * o código http de status da resposta (200 padrão) e
 * define também o cabeçalho apropriado.
 * @author Brunoggdev
 */
function json(mixed $param, int $codigo_http = 200, $flags = JSON_PRETTY_PRINT): string
{
    header('Content-Type: application/json');
    http_response_code($codigo_http);

    if ($param instanceof Entidade) {
        $param = $param->paraArray();
    }

    return json_encode($param, $flags);
}



/**
 * Retorna uma string com o conteúdo de um componente especificado se existir ou vazia caso contrário.
 * Também receber um array associativo de dados a serem utilizados.
 * @author Brunoggdev
 */
function componente(string $nome_componente, ?array $dados = []): string
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
function comp(string $nome_componente, ?array $dados = []): string
{
    $componente = "/componentes/$nome_componente";

    if (!file_exists(pasta_app("Views/$componente.php"))) {
        throw new Exception("Componente '$nome_componente' não encontrado.");
    }

    return view($componente, $dados);
}

/**
 * Retorna a string para importação do arquivo JavaScript na pasta publica 'js/' 
 * com o nome informado (se existir), podendo "defer".
 * @author Brunoggdev
 */
function importar_js(string $nome_arquivo, bool $defer = false): string
{
    $arquivo = "js/$nome_arquivo.js";

    if (!file_exists(pasta_public($arquivo))) {
        return '';
    }

    return '<script ' . ($defer ? 'defer ' : '') . ' src="' . url_base("$arquivo?v=") . VERSAO_APP . '"></script>';
}

/**
 * Retorna a string para importação do arquivo css na pasta publica 'css/' 
 * com o nome informado (se existir).
 * @author Brunoggdev
 */
function importar_css(string $nome_arquivo): string
{
    $arquivo = "css/$nome_arquivo.css";

    if (!file_exists(pasta_public($arquivo))) {
        return '';
    }

    return '<link rel="stylesheet" href="' . url_base("$arquivo?v=") . VERSAO_APP . '">';
}


/**
 * Define o código http desejado e para a execução; Opcionalmente
 * envia também uma string de resposta (pode ser uma view);
 * @author Brunoggdev
 */
function abortar(int $codigo_http, string $retorno = ''): void
{
    // limpa o buffer de saída se estiver ativo
    if (ob_get_status()) {
        ob_clean();
    }

    http_response_code($codigo_http);

    die($retorno);
}


/**
 * Redireciona o usuario para a rota informada, 
 * além de atualizar o código http (302 por padrão).
 * @author Brunoggdev
 */
function redirecionar(string $url = '', int $codigo = 302): Redirecionar
{
    $redirecionar = new Redirecionar();

    if (!empty($url)) {
        $redirecionar->para($url, $codigo);
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
function sessao(string|false $chave = false): mixed
{
    $sessao = new Sessao();

    return $chave ? $sessao->pegar($chave) : $sessao;
}



/**
 * Atalho para interagir com a classe de ClienteHttp para 
 * realizar uma requisicao get simples; 
 * `Se precisar de mais controle instancie a classe manualmente`.
 * @author Brunoggdev
 */
function requisicao_get(string $endpoint): ClienteHttp
{
    return (new ClienteHttp())->get($endpoint);
}


/**
 * Atalho para interagir com a classe de ClienteHttp para 
 * realizar uma requisicao post simples; 
 * `Se precisar de mais controle instancie a classe manualmente.`
 * @author Brunoggdev
 */
function requisicao_post(string $endpoint, array|string $dados): ClienteHttp
{
    return (new ClienteHttp())->post($endpoint, $dados);
}

/**
 * Higieniza o parametro informado (com strip_tags).
 * Se for um array, todos os campos serão higienizados de forma recursiva.
 * @link https://www.php.net/manual/en/function.strip-tags.php Mais informações sobre strip_tags.
 * @author Brunoggdev
 */
function higienizar(null|string|array $param): null|string|array
{

    if (is_null($param)) {
        return null;
    }


    if (is_string($param)) {
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
function encriptar(string $senha): string
{
    return password_hash($senha, PASSWORD_DEFAULT);
}



/**
 * Checa se a url atual corresponde à informada
 * @author Brunoggdev
 */
function url_igual(string $url): bool
{
    return $_SERVER['REQUEST_URI'] === $url;
}


/**
 * Checa se a url atual contém a parte informada
 * @author Brunoggdev
 */
function url_contem(string $parte): bool
{
    return str_contains($_SERVER['REQUEST_URI'], $parte);
}



/**
 * Atalho para acessar informações sobre a requisição atual
 */
function requisicao(): Requisicao
{
    return Requisicao::instancia();
}



/**
 * Acessa a query string da URL e retorna a chave desejada 
 * ou inteira se uma não for informada (ou null caso não existam);
 * @author Brunoggdev
 */
function query_string(?string $chave = null, $higienizar = false): ?string
{
    return Requisicao::query_string($chave, $higienizar);
}




/**
 * Adiciona um input hidden para especificar o tipo de requisicao desejado
 * @author Brunoggdev
 */
function form_metodo_http(string $metodo_http): string
{
    $metodo_http = strtoupper($metodo_http);
    return "<input type='hidden' name='_method' value=$metodo_http>";
}


/**
 * Abre uma tag form e configura os atributos action e method
 * @author Brunoggdev
 */
function abre_form(string $metodo_http, string $action): string
{
    $metodo_http = strtoupper($metodo_http);
    $action = str_starts_with($action, 'http') ? $action : url_base($action);

    if ($metodo_http === 'GET' || $metodo_http === 'POST') {
        $retorno = "<form action=$action method=$metodo_http>";
    } else {
        $retorno = "<form action=$action method=POST>";
        $retorno .= "\n" . form_metodo_http($metodo_http);
    }

    return $retorno;
}


/**
 * Fecha a tag form
 * @author Brunoggdev
 */
function fecha_form(): string
{
    return '</form>';
}


/**
 * Atalho para interagir com a classe Colecao
 * @author Brunoggdev
 */
function coletar(array $array): Colecao
{
    return new Colecao($array);
}

/**
 * Atalho conveniente para retornar uma instancia da Database;
 * Pode receber um array de configuração customizado.
 * @author Brunoggdev
 */
function db(?array $config = null): Database
{
    return Database::instancia($config);
}


/**
 * Retorna a SQL da tabela com o nome desejado (sem a data de criação)
 * @author Brunoggdev
 */
function tabela(string $tabela): Tabela
{
    $tabelas = array_values(array_filter(
        scandir(pasta_app('Database/tabelas')),
        fn($item) =>  str_ends_with($item, "$tabela.php")
    ));

    if (empty($tabelas)) {
        throw new Exception("Nenhuma tabela encontrada com o nome '$tabela'.");
    }

    return require pasta_app("Database/tabelas/$tabelas[0]");
}

/**
 * Extrai e retorna um item de um array associativo, modificando o array original 
 * (retorna null caso a chave não seja encontrada.)
 * @author Brunoggdev
 */
function extrair_item(string $chave, array &$array): mixed
{
    if (isset($array[$chave])) {
        $item = $array[$chave];
        unset($array[$chave]);
    } else {
        $item = null;
    }
    return $item;
}


/**
 * Gera um novo registro no arquivo de logs atual.
 * O arquivo terá no nome a data que o log foi gerado e 
 * ficará localizado na pasta logs que será automaticamente 
 * criada na raíz do projeto se já não existir
 */
function gerar_log(string $mensagem)
{

    $caminho_logs = PASTA_RAIZ . '/logs';
    if (!is_dir($caminho_logs)) {
        mkdir($caminho_logs, 0755, true);
    }


    $caminho_gitignore = $caminho_logs . '/.gitignore';
    if (!file_exists($caminho_gitignore)) {
        file_put_contents($caminho_gitignore, '*');
    }

    $caminho_log = $caminho_logs . '/log_' . date('d-m-Y') . '.log';

    $entrada_log = '[' . date('H:i:s') . '] - ' . $mensagem . "\n";

    file_put_contents($caminho_log, $entrada_log, file_exists($caminho_log) ? FILE_APPEND : 0);
}



/**
 * Acessa o arquivo de configuração desejado e devolve o que quer que seja retornado nele 
 * (geralmente um array, mas não limitado a isso).
 * @return array|mixed
 */
function config(string $config): mixed
{
    if (str_contains($config, '.')) {
        [$config, $chaves] = explode('.', $config, 2);
    }

    $arquivo = pasta_app("/Config/$config.php");

    if (!is_file($arquivo)) {
        throw new Exception("O arquivo de configuração desejado não foi encontrado em '$arquivo'.");
    }

    $arquivo = require $arquivo;

    return isset($chaves) ? dot_notation($chaves, $arquivo) : $arquivo;
}


/**
 * Acessa e retorna a chave desejada (ou null caso não exista) utilizando "dot notation" (chaves separadas por pontos)
 * @example $chaves 'chave1.chave2.chave3' equivale a $array['chave1']['chave2']['chave3]
 * @author Brunoggdev
 */
function dot_notation(string $chaves, array $array)
{
    if (empty($chaves)) {
        return null;
    }

    $chaves = explode('.', $chaves);
    $retorno = $array;

    foreach ($chaves as $chave) {
        if (!isset($retorno[$chave])) {
            return null;
        }

        $retorno = $retorno[$chave];
    }

    return $retorno;
}
