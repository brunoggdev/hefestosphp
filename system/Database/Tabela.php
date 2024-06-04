<?php

namespace Hefestos\Database;


/**
* Encadeie métodos para montar a estrutuda de sua tabela (foreign keys sempre por último!).
* @author Brunoggdev
*/
class Tabela
{
    private string $sql = '';

    public function __construct(public string $nome_tabela)
    {
        $this->sql = "CREATE TABLE $nome_tabela (";
    }

    /**
    * Adiciona a coluna id padrão na tabela;
    * @author Brunoggdev
    */
    public function id(string $coluna = 'id'): self {
        if((require pasta_app('Config/database.php'))['driver'] == 'mysql'){
            $primary_key = "$coluna int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        }else{
            $primary_key = "$coluna INTEGER PRIMARY KEY, ";
        }
        
        $this->sql .= $primary_key;
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo varchar com o número de caracteres informado e se deve ou não ser único.
    * @author Brunoggdev
    */
    public function varchar(string $coluna, int $tamanho = 255, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        $this->sql .= "$coluna VARCHAR($tamanho) ";
        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }

        $this->sql .= ', ';
        return $this;
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
        $this->sql .= "$coluna TEXT ";

        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        $this->sql .= ', ';
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo boolean.
    * @author Brunoggdev
    */
    public function boolean(string $coluna, bool $nullable = false, mixed $default = false): self
    {
        $this->sql .= "$coluna BOOLEAN ";

        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT $default";
        }
        
        $this->sql .= ', ';
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo timestamp
    * @author Brunoggdev
    */
    public function timestamp(string $coluna, bool $unique = false, bool $nullable = false, mixed $default = 'CURRENT_TIMESTAMP'): self
    {
        $this->sql .= "$coluna TIMESTAMP ";

        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_TIMESTAMP' || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $this->sql .= "DEFAULT CURRENT_TIMESTAMP";
        }
        
        $this->sql .= ', ';
        return $this;
    }
    
    /**
    * Adiciona uma coluna do tipo date
    * @author Brunoggdev
    */
    public function date(string $coluna, bool $unique = false, bool $nullable = false, string|null $default = 'CURRENT_DATE'): self
    {
        $this->sql .= "$coluna DATE ";

        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_DATE' || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $this->sql .= "DEFAULT CURRENT_DATE";
        }
        
        $this->sql .= ', ';
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo time
    * @author Brunoggdev
    */
    public function time(string $coluna, bool $unique = false, bool $nullable = false, string|null $default = 'CURRENT_TIME'): self
    {
        $this->sql .= "$coluna TIME ";
        
        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_TIME'|| $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $this->sql .= "DEFAULT CURRENT_DATE";
        }
        
        $this->sql .= ', ';
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo datetime
    * @author Brunoggdev
    */
    public function datetime(string $coluna, bool $unique = false, bool $nullable = false, string|null $default = 'CURRENT_TIMESTAMP'): self
    {
        $this->sql .= "$coluna DATETIME ";

        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default !== 'CURRENT_TIMESTAMP' || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        } else {
            $this->sql .= 'DEFAULT CURRENT_TIMESTAMP';
        }
        
        $this->sql .= ', ';
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo float.
    * @author Brunoggdev
    */
    public function int(string $column, int $tamanho = 11, bool $unique = false, bool $nullable = false, int|bool $default = false): self
    {
        $this->sql .= "$column INT($tamanho) ";
        
        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        $this->sql .= ', ';
        return $this;
    }


    /**
    * Adiciona uma coluna do tipo float.
    * @author Brunoggdev
    */
    public function float(string $coluna, int $total_digitos, int $decimais, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        $this->sql .= "$coluna FLOAT($total_digitos, $decimais) ";
                
        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        $this->sql .= ', ';
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo json (nem todos os tipos de sql suportam isso).
    * @author Brunoggdev
    */
    public function json(string $coluna, bool $unique = false, bool $nullable = false, mixed $default = false): self
    {
        $this->sql .= "$coluna JSON ";
                
        if($unique){
            $this->sql .= 'UNIQUE ';
        }
        if (!$nullable) {
            $this->sql .= 'NOT NULL ';
        }
        if($default || $default === null){
            $this->sql .= $default === null ? 'DEFAULT NULL' : "DEFAULT '$default'";
        }
        
        $this->sql .= ', ';
        return $this;
    }

    /**
    * Adiciona uma chave estrangeira da coluna desejada para a tabela e coluna especificadas 
    * (lembre-se de usar este metodo apenas no final da sql).
    * @author Brunoggdev
    */
    public function foreignKey(string $coluna, string $tabela_ref, string $coluna_ref): self
    {
        $this->sql .= "FOREIGN KEY ($coluna) REFERENCES $tabela_ref($coluna_ref)";
        return $this;
    }

    public function __toString(): string
    {
        return  rtrim($this->sql, ", ") . ') ENGINE=InnoDB;';
    }
}
