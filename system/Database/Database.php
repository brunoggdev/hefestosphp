<?php

namespace System\Database;
use System\Ferramentas\Colecao;

use PDO, PDOStatement;

/**
* Responsável pela conexão, montagem e execução de queries no banco de dados.
* @author brunoggdev
*/
class Database
{

    protected PDO $connection;
    protected string $query = '';
    protected $params = [];
    protected PDOStatement $queryInfo;
    private bool $comoArray = false;

    /**
    * Requisita um array contendo [$host, $nomeBD, $usuario, $senha]
    * para se conectar ao banco de dados e instanciar o PDO.
    * @author brunoggdev
    */
    public function __construct()
    {
        // nota: Injeção de dependencias, eu sei
        [$host, $nomeDB, $usuario, $senha] = require pasta_app('Config/database.php');

        $dsn = "mysql:host=$host;dbname=$nomeDB";

        $this->connection = new PDO($dsn, $usuario, $senha, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]); 
    }


    /**
    * Adiciona um SELECT na consulta
    * @author brunoggdev
    */
    public function select(string $table, array $colunas = ['*']): self
    {
        $colunas = implode(', ', $colunas);

        $this->query = "SELECT $colunas FROM $table";

        return $this;
    }



    /**
    * Adiciona um INSERT na consulta
    * @author brunoggdev
    */
    public function insert(string $table, array $params):bool
    {
        $this->params = $params;

        $colunas = implode(', ', array_keys($params));
        $values = ':' . implode(', :', array_keys($params));

        $this->query = "INSERT INTO $table ($colunas) VALUES($values)";

        $query = $this->executarQuery();
        
        return $query->rowCount() > 0 ? true : false;

    }
   
   
   
    /**
    * Cria uma sql para DELETE
    * @author Brunoggdev
    */
    public function delete(string $table, array $where = []):bool
    {

        $this->query = "DELETE FROM $table";
        $this->where($where);


        $query = $this->executarQuery();
        return $query->rowCount() > 0 ? true : false;
    }



    /**
    * Adiciona um UPDATE na consulta
    * @author brunoggdev
    */
    public function update(string $table, array $params, array $where = []):bool
    {
        $this->params = $params;

        $novosValores = '';

        foreach ($params as $key => $value) {
            $novosValores .= "$key = :$key";
            if($key !== array_key_last($params)){
                $novosValores .= ', ';
            }
        }

        $this->query = "UPDATE $table SET $novosValores";
        $this->where($where);


        $query = $this->executarQuery();
        return $query->rowCount() > 0 ? true : false;

    }



    /**
    * Adiciona um WHERE na consulta
    * @param Array $params Associative array 
    * @example $params ['id' => '2'] equals: id = 2 in the sql
    * @example $params ['id' => '>= 1'] equals: id >= 1 in the sql
    * @author brunoggdev
    */
    public function where(array $params): self
    {
        
        foreach ($params as $key => $value) {
            
            if (! str_contains($this->query, 'WHERE') ) {
                $this->query .= ' WHERE ';
            }

            // retirando pontos pois não são aceitos nas chaves de array
            $chave = str_replace('.', '', $key);
            
            // Assume "=" caso nenhum operador seja informado no valor
            if(!preg_match('/^(=|<|>|<=|>=|like)/i', $value)){
                $this->params[$chave] = $value;
                $this->query .= "$key = :$chave ";
                
            }else{
                
                $pieces = explode(' ', $value);
                $operador = $pieces[0];
                $this->params[$chave] = $pieces[1];
                
                $this->query .= "$key $operador :$chave ";
                
            }

            
            if($key !== array_key_last($params)){
                $this->query .= 'AND ';
            }

        }

        return $this;
    }


    /**
    * Adiciona um OR na consulta
    * @author Brunoggdev
    */
    public function orWhere(array $params):self
    {
        $this->query .= ' OR ';
        $this->where($params);
        
        return $this;
    }


    /**
    * Adiciona um JOIN na consulta
    * @author Brunoggdev
    */
    public function join(string $tabelaParaJoin, string $condicao, ?string $tipoDeJoin = 'INNER'):self
    {
        $this->query .= " $tipoDeJoin JOIN $tabelaParaJoin ON $condicao";

        return $this;
    }


    /**
    * Adiciona um ORDER BY na query
    * @author brunoggdev
    */
    public function orderBy(string $column, string $order = 'ASC'):self
    {
        $this->query .= "ORDER BY $column $order ";
        
        return $this;
    }


    /**
    * Recebe uma sql completa para consultar no banco de dados.
    * @example $sql SELECT * FROM users WHERE id >= :id
    * @example $params ['id' => 1]
    * @author brunoggdev
    */
    public function query(string $sql, array $params = [])
    {
        $this->query = $sql;
        $this->params = $params;
        $this->executarQuery();

        return $this;
    }


    /**
    * Pega o primeiro resultado da consulta
    * @author brunoggdev
    */
    public function primeiro(int $fetchMode = PDO::FETCH_OBJ)
    {
        $query = $this->executarQuery();
        
        return $this->comoArray ? $query->fetch(PDO::FETCH_ASSOC) : new Colecao( $query->fetch($fetchMode) );
    }


    /**
    * Retorna todos os resultados da consulta
    * @author brunoggdev
    */
    public function todos(int $fetchMode = PDO::FETCH_OBJ)
    {
        $query = $this->executarQuery();

        return $this->comoArray ? $query->fetchAll(PDO::FETCH_ASSOC) : new Colecao( $query->fetchAll($fetchMode) );
    }


    /**
    * Executa a consulta no banco de dados e retorna o PDOStatement
    * @author brunoggdev
    */
    protected function executarQuery():PDOStatement
    {
        $query = $this->connection->prepare($this->query);
        
        $this->queryInfo = $query;
        
        $query->execute($this->params);

        $this->params = [];
        $this->queryInfo = $query;

        return $query;
    }


    /**
    * Retorna a string montada da consulta
    * @author brunoggdev
    */
    public function stringDaConsulta():string
    {
        return $this->query;
    }


    /**
    * Retorna os erros que ocorreram durante a execução da SQL
    * @author brunoggdev
    */
    public function erros():array
    {
        return $this->queryInfo->errorInfo();
    }


    /**
    * Define o retorno do banco de dados como um array associativo
    * @author Brunoggdev
    */
    public function comoArray():self
    {
        $this->comoArray = true;

        return $this;
    }

}
