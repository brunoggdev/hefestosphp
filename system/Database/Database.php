<?php

namespace Hefestos\Database;

use Exception;
use PDO, PDOStatement;

/**
 * Responsável pela conexão, montagem e execução de queries no banco de dados.
 * @author brunoggdev
 */
class Database
{
    protected static ?self $instancia = null;
    protected ?PDO $conexao;
    protected ?PDOStatement $query_info;
    protected string $tabela;
    protected string $query = '';
    protected array $params = [];
    protected string $classe_de_retorno;
    protected bool $checar_nome_tabela = true;
    protected bool $como_array = true;
    protected int $fetch_mode = PDO::FETCH_ASSOC;
    private bool $driver_mysql;

    /**
     * Busca o array de conexão com o banco de dados e instancia o PDO.
     * Pode receber uma conexão alternativa na forma de um array 
     * com as mesmas chaves do padrão na pasta config.
     * @author brunoggdev
     */
    public function __construct(?array $db_config = null)
    {
        $db_config ??= config('database');

        $this->driver_mysql = $db_config['driver'] == 'mysql';

        [$dsn, $usuario, $senha] = $this->formatarConexao($db_config);

        if (defined('RODANDO_TESTES')) {
            $dsn = 'sqlite:' . PASTA_RAIZ . 'app/Database/sqlite/testes.sqlite';
        }

        $this->conexao = new PDO($dsn, $usuario, $senha, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }




    /**
     * Formata as informações de conexão com o banco, retornando o dsn, usuario e senha
     * @author Brunoggdev
     */
    private function formatarConexao(array $config): array
    {
        $dsn = $this->driver_mysql
            ? "mysql:host=$config[host];dbname=$config[nome_db]"
            : 'sqlite:' . PASTA_RAIZ . $config['sqlite'];

        return [$dsn, $config['usuario'] ?? null, $config['senha'] ?? null];
    }




    /**
     * Retorna a conexão ativa do banco de dados (singleton).
     * @param array $config Array associativo com as chaves 'driver' (sqlite ou mysql),
     * 'usuario', 'senha', 'host' e 'nome_db' se for mysql ou, caso contrário, 'sqlite' com o caminho do arquivo.
     * @author Brunoggdev
     */
    public static function instancia(?array $config = null): self
    {
        if (!is_null(self::$instancia)) {
            self::$instancia->tabela('');

            return self::$instancia;
        }

        self::$instancia = new self($config);

        return self::$instancia;
    }




    /**
     * Fecha a conexão com o banco de dados.
     * @author Brunoggdev
     */
    public function fechar(): void
    {
        $this->conexao = null;
        $this->query_info = null;
    }




    /**
     * Define a tabela na qual o as próximas consultas serão executadas
     */
    public function tabela(string $tabela): self
    {
        $this->tabela = $tabela;

        return $this;
    }




    /**
     * Adiciona um SELECT na consulta
     * @author brunoggdev
     */
    public function select(string|array $colunas = '*'): self
    {
        if (is_array($colunas)) {
            $colunas = implode(', ', $colunas);
        }

        $this->query = "SELECT $colunas FROM $this->tabela";

        return $this;
    }




    /**
     * Adiciona um INSERT na consulta. 
     * Se informado um objeto como parâmetro ele será convertido para array.
     * Retorna o id inserido (por padrão) ou um bool para sucesso ou falha.
     * @author brunoggdev
     */
    public function insert(array|object $params, bool $retornar_id = true): string|bool
    {
        $this->params = (array) $params;

        $colunas = implode(', ', array_keys($this->params));
        $valores = ':' . implode(', :', array_keys($this->params));

        $this->query = "INSERT INTO $this->tabela ($colunas) VALUES($valores)";

        $resultado = $this->executarQuery();

        return $retornar_id ? $this->idInserido() : $resultado;
    }




    /**
     * Cria uma sql para DELETE
     * @return bool true se sucesso, false caso contrário;
     * @author Brunoggdev
     */
    public function delete(array|string $where): bool
    {

        $this->query = "DELETE FROM $this->tabela";
        $this->where($where);


        return $this->executarQuery();
    }



    /**
     * Define o SET de um UPDATE no SQL
     */
    public function set(array|object $params): self
    {
        if (empty($params)) {
            return $this;
        }

        $this->params = (array) $params;

        $novos_valores = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($params)));

        $this->query = "UPDATE $this->tabela SET $novos_valores";

        return $this;
    }



    /**
     * Executa o UPDATE definido com o set() **OU** pelos parametros informados aqui
     * @author brunoggdev
     */
    public function update(array|object|null $params = null, array|string $where = []): bool
    {
        // se a query estiver vazia o set() não foi chamado ainda
        if (empty($this->query)) {
            $this->set($params);
        }

        // Se foi informado um where, nós o usaremos apenas se já não tiver algum definido anteriormente
        if (!empty($where) && !str_contains($this->query, 'WHERE')) {
            $this->where($where);
        }

        return $this->executarQuery();
    }




    /**
     * Adiciona um WHERE na consulta.
     * Suporta três sintaxes:
     * - Se dois parametros forem informados serão tratados como coluna e valor;
     * - Se apenas um for informado e for um array associativo as chave => valor serão coluna e valor;
     * - Se apenas um for informado e for uma string será tratada como uma SQL pura (cuidado com SQL injection).
     * Onde quer que seja informada a coluna ela pode ser acompanhada de operadores como '>', '!=', 'like' etc (padrão é =).
     * @example $params ['id' => '2'] significa: id = 2 na sql
     * @example $params ['id >=' => '1'] significa: id >= 1 na sql
     * @author brunoggdev
     */
    public function where(array|string $params, mixed $valor = null): self
    {
        if (empty($params)) {
            return $this;
        }

        if (empty($this->query)) {
            $this->select();
        }

        if (! str_contains($this->query, 'WHERE')) {
            $this->query .= ' WHERE ';
        } else {
            $this->query .= ' AND ';
        }

        if (is_string($params)) {
            if (func_num_args() === 1) {
                $this->query .= $params;
                return $this;
            }

            $params = [$params => $valor];
        }


        foreach ($params as $key => $value) {
            // retirando pontos pois não são aceitos nas chaves de array
            $chave = str_replace('.', '', $key);

            // Assume "=" caso nenhum operador seja informado no valor
            if (!preg_match('/(=|<|>|<=|>=|like)$/i', $chave)) {
                $this->params[] = $value;
                $this->query .= "$key = ? ";
            } else {
                $this->params[] = $value;
                $this->query .= "$chave ? ";
            }

            if ($key !== array_key_last($params)) {
                $this->query .= 'AND ';
            }
        }

        return $this;
    }



    public function whereIn(string $coluna, array $valores): self
    {
        if (empty($coluna) || empty($valores)) {
            return $this;
        }

        if (empty($this->query)) {
            $this->select();
        }

        if (! str_contains($this->query, 'WHERE')) {
            $this->query .= ' WHERE ';
        }

        $this->params[] = implode(', ', $valores);
        $this->query .= "$coluna IN (?) ";

        return $this;
    }


    public function orWhereIn(string $coluna, array $valores): self
    {
        if (empty($coluna) || empty($valores)) {
            return $this;
        }

        $this->query .= ' OR ';
        $this->query .= "$coluna IN (?) ";
        $this->params[] = implode(', ', $valores);

        return $this;
    }




    /**
     * Adiciona um OR na consulta e em seguida um where novamente
     * @author Brunoggdev
     */
    public function orWhere(array|string $params, mixed $valor = null): self
    {
        $this->query .= ' OR ';
        $this->where($params, $valor);

        return $this;
    }



    /**
     * Adiciona um like na consulta para o valor informado
     * @author Brunoggdev
     */
    public function like(string $coluna, mixed $valor): self
    {
        $this->where("$coluna like", $valor);

        return $this;
    }



    /**
     * Adiciona um OR na consulta e em seguida um like
     * @author Brunoggdev
     */
    public function orLike(string $coluna, mixed $valor): self
    {
        $this->query .= ' OR ';
        $this->where("$coluna like", $valor);

        return $this;
    }



    /**
     * Adiciona uma verificação de coluna com valor nulo na consulta
     */
    public function ondeForNulo(string $coluna): self
    {
        $this->where("$coluna IS NULL");

        return $this;
    }



    /**
     * Adiciona uma verificação de coluna com valor diferente de nulo na consulta
     */
    public function ondeNaoForNulo(string $coluna): self
    {
        $this->where("$coluna IS NOT NULL");

        return $this;
    }




    /**
     * Adiciona um JOIN na consulta
     * @author Brunoggdev
     */
    public function join(string $tabela_para_join, string $condicao, ?string $tipo_de_join = 'INNER'): self
    {
        $this->query .= " $tipo_de_join JOIN $tabela_para_join ON $condicao";

        return $this;
    }




    /**
     * Adiciona um ORDER BY na query
     * @author brunoggdev
     */
    public function orderBy(string $coluna, string $order = 'ASC'): self
    {
        if (empty($this->query)) {
            $this->select('*');
        }

        $this->query .= "ORDER BY $coluna $order ";

        return $this;
    }



    /**
     * Adiciona um GROUP BY na query
     * @author brunoggdev
     */
    public function groupBy(string|array $colunas): self
    {
        if (empty($this->query)) {
            $this->select('*');
        }

        if (is_array($colunas)) {
            $colunas = implode(', ', $colunas);
        }

        $this->query .= "GROUP BY $colunas";

        return $this;
    }



    /**
     * Adiciona um LIMIT na query
     * @author brunoggdev
     */
    public function limit(string|int $quantidade): self
    {
        if (empty($this->query)) {
            $this->select('*');
        }


        $this->query .= "LIMIT $quantidade";

        return $this;
    }



    /**
     * Recebe uma sql completa para consultar no banco de dados.
     * Se informado um objeto como parâmetro ele será convertido para array.
     * @example $sql SELECT * FROM users WHERE id >= :id
     * @example $params ['id' => 1]
     * @return bool|PDOStatement false em caso de falha ou PDOStatement em caso de sucesso (que avalila para true)
     * @author brunoggdev
     */
    public function executar(string $sql, array|object $params = []): bool|PDOStatement
    {
        $this->query = $sql;
        $this->params = (array) $params;
        $this->checar_nome_tabela = false;

        if (!$this->executarQuery()) {
            return false;
        }

        return $this->query_info;
    }




    /**
     * Pega o primeiro resultado da consulta; Opcionalmente, recebe o nome de coluna especifica para que apenas o valor dessa coluna seja retornado.
     * @author brunoggdev
     */
    public function primeiro(?string $nome_coluna_especifica = null): mixed
    {
        if (empty($this->query)) {
            $this->select($nome_coluna_especifica ?? '*');
        }

        $resultado = $this->executarQuery(true)->fetch($this->fetch_mode);

        if ($nome_coluna_especifica) {
            return $resultado[$nome_coluna_especifica] ?? null;
        }

        return $this->como_array ? $resultado : $this->retornarObjeto($resultado);
    }




    /**
     * Retorna todos os resultados da consulta montada até agora.
     * @param bool $coluna_unica retorna diretamente os valores da coluna sendo buscada
     * @example $coluna_unica $db->tabela('pets')->select('nome')->todos(true);  //retorna diretamente um array com todos os nomes
     * @author brunoggdev
     */
    public function todos(bool $coluna_unica = false): array
    {
        if (empty($this->query)) {
            $this->select('*');
        }

        $fetch_mode = $coluna_unica ? PDO::FETCH_COLUMN : $this->fetch_mode;
        $resultado = $this->executarQuery(true)->fetchAll($fetch_mode);

        return $this->como_array ? $resultado : $this->retornarObjeto($resultado, true);
    }



    /**
     * Retorna o resultado da consulta no formato do objeto definido
     */
    public function retornarObjeto(array $resultado, bool $todos = false): mixed
    {

        $classe = $this->classe_de_retorno;

        if ($todos) {
            return array_map(fn($resultado) => new $classe($resultado), $resultado);
        }

        return new $classe($resultado);
    }



    /**
     * Executa a sql no banco de dados e retorna o boolean do resultado ou,
     * opcionalmente, o PDOStatement;
     * @author brunoggdev
     */
    protected function executarQuery(bool $retornar_query = false): bool|PDOStatement
    {

        if (empty($this->tabela) && $this->checar_nome_tabela) {
            throw new Exception('Não foi definida a tabela onde deve ser realizada a consulta.');
        }

        // resetando a checagem sempre que esta função for chamada
        $this->checar_nome_tabela = true;

        $query = $this->conexao->prepare($this->query);

        $this->query_info = $query;

        $resultado = $query->execute($this->params);

        $this->query_info = $query;
        $this->query = '';
        $this->params = [];

        return $retornar_query ? $query : $resultado;
    }




    /**
     * Retorna a string montada da consulta
     * @author brunoggdev
     */
    public function stringDaConsultaSql(): string
    {
        return $this->query;
    }




    /**
     * Retorna o número de linhas afetadas pela ultima sql
     * @author Brunoggdev
     */
    public function linhasAfetadas(): int
    {
        return $this->query_info->rowCount();
    }




    /**
     * Retorna o último id inserido pela sql mais recente
     * @author Brunoggdev
     */
    public function idInserido(): string|false
    {
        return $this->conexao->lastInsertId();
    }




    /**
     * Retorna os erros que ocorreram durante a execução da SQL
     * @author brunoggdev
     */
    public function erros(): array
    {
        return $this->query_info->errorInfo();
    }


    /**
     * Retorna o PDO da conexão atual.
     * @author brunoggdev
     */
    public function pdo(): ?PDO
    {
        return $this->conexao;
    }


    /**
     * Retorna o PDOStatement da última operação.
     * @author brunoggdev
     */
    public function pdoStatement(): ?PDOStatement
    {
        return $this->query_info;
    }




    /**
     * Define o retorno do banco de dados como um array associativo
     * @author Brunoggdev
     */
    public function comoArray(): self
    {
        $this->como_array = true;

        return $this;
    }




    /**
     * Define o retorno do banco de dados como um objeto (ou array de objetos) da classe informada;
     * O array de resultados será passado para o construtor da classe desejada.
     * @param string $classe SuaClasse::class - O "nome qualificado" da classe desejada
     * @author Brunoggdev
     */
    public function comoObjeto(string $classe): self
    {
        $this->como_array = false;
        $this->classe_de_retorno = $classe;

        return $this;
    }




    /**
     * Define o fetch mode do PDO
     * @author Brunoggdev
     */
    public function fetchMode(int $fetch_mode): self
    {
        $this->fetch_mode = $fetch_mode;

        return $this;
    }



    /**
     * Retorna da tabela desejada a linha (ou coluna especifica) com o id informado, podendo retornar uma coluna especifica
     * @author Brunoggdev
     */
    public function buscar(int|string $id, ?string $coluna = null): mixed
    {
        return $this->primeiroOnde(['id' => $id], $coluna);
    }



    /**
     * Retorna o primeiro resultado para o 'where' informado; Opcionalmente, recebe o nome de coluna especifica para que apenas os dados dessa coluna sejam retornados.
     */
    public function primeiroOnde(array|string $where, ?string $nome_coluna_especifica = null): mixed
    {

        if ($nome_coluna_especifica) {
            $this->select([$nome_coluna_especifica]);
        }

        return $this->where($where)->primeiro($nome_coluna_especifica);
    }




    /**
     * Recebe e executa uma função onde podem ser realizadas operações no banco de dados
     * dentro do contexto de uma transação ativa.
     * 
     * Se nenhum erro ocorrer, persiste todas as operações no banco e retorna true. 
     * Do contrário, desfaz todas as operações no banco e retorna false.
     * 
     * ATENÇÃO: A engine MyISAM do MySQL não suporta transações, use InnoDB. 
     * @see https://www.php.net/manual/en/pdo.begintransaction.php
     */
    public function transacao(callable $operacoes): bool
    {
        $this->conexao->beginTransaction();

        try {

            $operacoes($this);

            if (!$this->conexao->commit()) {
                $this->conexao->rollBack();
                return false;
            }

            return true;
        } catch (\Throwable) {

            $this->conexao->rollBack();
            return false;
        }
    }



    /**
     * Retorna um array com os nomes de todas as tabelas existentes no banco de dados da conexão atual
     * @return string[]
     */
    public function listarTabelas(): array
    {
        $consulta = $this->driver_mysql
            ? 'SHOW TABLES'
            : 'SELECT name FROM sqlite_master WHERE type="table";';

        return $this->executar($consulta)->fetchAll(PDO::FETCH_COLUMN);
    }



    /**
     * Retorna um array com os nomes de todas as colunas da tabela definida
     * @return string[]
     */
    public function listarColunas(): array
    {
        if (empty($this->tabela) && $this->checar_nome_tabela) {
            throw new Exception('Não foi definida a tabela onde deve ser realizada a consulta.');
        }

        if ($this->driver_mysql) {
            return $this->executar("SHOW COLUMNS FROM $this->tabela")->fetchAll(PDO::FETCH_COLUMN, 0);
        }

        return $this->executar("PRAGMA table_info($this->tabela);")->fetchAll(PDO::FETCH_COLUMN, 1);
    }


    /**
     * Retorna um array com os nomes de todas as colunas que possuem uma foreign key na tabela definida
     * @return string[]
     */
    public function listarForeignKeys(): array
    {
        if (empty($this->tabela) && $this->checar_nome_tabela) {
            throw new Exception('Não foi definida a tabela onde deve ser realizada a consulta.');
        }

        if ($this->driver_mysql) {
            return $this->executar("
                SELECT 
                    COLUMN_NAME
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE 
                    TABLE_NAME = '$this->tabela' AND 
                    CONSTRAINT_NAME != 'PRIMARY'
            ")->fetchAll(PDO::FETCH_COLUMN, 0);
        }

        return $this->executar("PRAGMA foreign_key_list($this->tabela);")->fetchAll(PDO::FETCH_COLUMN, 1);
    }
}
