<?php

namespace Hefestos\Database;


/**
* Encadeie métodos para montar a estrutuda de sua tabela (foreign keys sempre por último!).
* @author Brunoggdev
*/
class Tabela
{
    private string $sql;
    private bool $driver_mysql;
    private array $colunas = [];
    private array $foreign_keys = [];

    public function __construct(public string $nome)
    {
        $this->driver_mysql = config('database')['driver'] == 'mysql';
        $this->sql = "CREATE TABLE $nome (";
    }


    /**
     * Adiciona a nova coluna na sql de criação da tabela
    */
    private function adicionarColuna(string $coluna, $definicao): self
    {
        $this->colunas[$coluna] = $definicao;

        $this->sql .= $definicao . ', ';

        return $this;
    }
 
 
    /**
     * Adiciona a nova foreign key na sql de criação da tabela
    */
    private function adicionarForeignKey(string $coluna, $definicao): self
    {
        $this->foreign_keys[$coluna] = $definicao;

        $this->sql .= $definicao . ', ';

        return $this;
    }



    /**
     * Retorna a sql de inserção de novas colunas e foreign keys se existirem
    */
    private function atualizarSchema(array $colunas, array $fks):string
    {
        $novas_colunas = array_diff(array_keys($this->colunas), $colunas);
        $novas_fks = array_diff(array_keys($this->foreign_keys), $fks);
        
        $alter_sql = "ALTER TABLE $this->nome ";

        foreach ($novas_colunas as $novas_coluna) {
            $alter_sql .= 'ADD COLUMN '.$this->colunas[$novas_coluna] . ', ';
        }

        $alter_sql = rtrim($alter_sql, ", "). "; ALTER TABLE $this->nome ";

        foreach ($novas_fks as $novas_fk) {
            $alter_sql .= 'ADD '.$this->foreign_keys[$novas_fk] . ', ';
        }
        $alter_sql = rtrim($alter_sql, ", ").';';

        return $alter_sql;
    }



    /**
    * Adiciona a coluna id padrão na tabela;
    * @author Brunoggdev
    */
    public function id(string $coluna = 'id'): self {
        $definicao = $this->driver_mysql == 'mysql'
            ? "$coluna int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY" // primary key mysql
            : "$coluna INTEGER PRIMARY KEY"; // primary key sqlite

        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Adiciona uma coluna do tipo varchar com o número de caracteres informado e se deve ou não ser único.
    * @author Brunoggdev
    */
    public function varchar(string $coluna, int $tamanho = 255, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        $definicao = "$coluna VARCHAR($tamanho) ";
        
        if($unique){
            $definicao .= 'UNIQUE ';
        }

        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }

        if($default || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }

        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Apenas um atalho mais legivel para o metodo varchar com 255 caracteres.
    * @author Brunoggdev
    */
    public function string(string $coluna, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        // 250 quando unique para evitar erros de sql
        return $this->varchar($coluna, $unique ? 250 : 255, $unique, $nullable, $default);
    }

    /**
    * Adiciona uma coluna do tipo text.
    * @author Brunoggdev
    */
    public function text(string $coluna, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        $definicao = "$coluna TEXT ";

        if($unique){
            $definicao .= 'UNIQUE ';
        }
        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }
        if($default || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Adiciona uma coluna do tipo boolean.
    * @author Brunoggdev
    */
    public function boolean(string $coluna, bool $nullable = false, mixed $default = false): self
    {
        $definicao = "$coluna BOOLEAN ";

        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }
        if($default || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT $default";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Adiciona uma coluna do tipo timestamp
    * @author Brunoggdev
    */
    public function timestamp(string $coluna, bool $unique = false, bool $nullable = false, mixed $default = 'CURRENT_TIMESTAMP'): self
    {
        $definicao = "$coluna TIMESTAMP ";

        if($unique){
            $definicao .= 'UNIQUE ';
        }
        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_TIMESTAMP' || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $definicao .= "DEFAULT CURRENT_TIMESTAMP";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }
    
    /**
    * Adiciona uma coluna do tipo date
    * @author Brunoggdev
    */
    public function date(string $coluna, bool $unique = false, bool $nullable = false, string|null $default = 'CURRENT_DATE'): self
    {
        $definicao = "$coluna DATE ";

        if($unique){
            $definicao .= 'UNIQUE ';
        }
        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_DATE' || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $definicao .= "DEFAULT CURRENT_DATE";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Adiciona uma coluna do tipo time
    * @author Brunoggdev
    */
    public function time(string $coluna, bool $unique = false, bool $nullable = false, string|null $default = 'CURRENT_TIME'): self
    {
        $definicao = "$coluna TIME ";
        
        if($unique){
            $definicao .= 'UNIQUE ';
        }
        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_TIME'|| $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $definicao .= "DEFAULT CURRENT_DATE";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Adiciona uma coluna do tipo datetime
    * @author Brunoggdev
    */
    public function datetime(string $coluna, bool $unique = false, bool $nullable = false, string|null $default = 'CURRENT_TIMESTAMP'): self
    {
        $definicao = "$coluna DATETIME ";

        if($unique){
            $definicao .= 'UNIQUE ';
        }
        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_TIMESTAMP' || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $definicao .= 'DEFAULT CURRENT_TIMESTAMP';
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Adiciona uma coluna do tipo float.
    * @author Brunoggdev
    */
    public function int(string $coluna, bool $unique = false, bool $nullable = false, int|bool $default = false): self
    {
        $definicao = "$coluna INT UNSIGNED ";
        
        if ($unique) {
            $definicao .= 'UNIQUE ';
        }

        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }

        if($default || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }


    /**
    * Adiciona uma coluna do tipo float.
    * @author Brunoggdev
    */
    public function float(string $coluna, int $total_digitos, int $decimais, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        $definicao = "$coluna FLOAT($total_digitos, $decimais) ";
                
        if ($unique) {
            $definicao .= 'UNIQUE ';
        }

        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }

        if($default || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }

    /**
    * Adiciona uma coluna do tipo json (nem todos os tipos de sql suportam isso).
    * @author Brunoggdev
    */
    public function json(string $coluna, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        $definicao = "$coluna JSON ";
                
        if ($unique) {
            $definicao .= 'UNIQUE ';
        }

        if (!$nullable) {
            $definicao .= 'NOT NULL ';
        }

        if($default || $default === null){
            $definicao .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        return $this->adicionarColuna($coluna, $definicao);
    }

    
    /**
    * Adiciona uma chave estrangeira da coluna desejada para a tabela e coluna especificadas 
    * (lembre-se de usar este metodo apenas no final da sql).
    * @author Brunoggdev
    */
    public function foreignKey(string $coluna, string $tabela_ref, string $coluna_ref): self
    {
        $definicao = "FOREIGN KEY ($coluna) REFERENCES $tabela_ref($coluna_ref)";
        return $this->adicionarForeignKey($coluna, $definicao);
    }

    public function __toString(): string
    {
        return  rtrim($this->sql, ", ") . ')' . ($this->driver_mysql ? ' ENGINE=InnoDB;' : ';');
    }
}
