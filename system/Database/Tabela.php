<?php

namespace System\Database;


/**
* Encadeie métodos para montar a estrutuda de sua tabela (foreign keys sempre por último!).
* @author Brunoggdev
*/
class Tabela
{
    private string $sql = '';

    public function __construct(string $tablename)
    {
        $this->sql = "CREATE TABLE $tablename (";
    }

    /**
    * Adiciona a coluna id padrão na tabela;
    * @author Brunoggdev
    */
    public function id(string $coluna = 'id'): self {
        $this->sql .= "$coluna int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo varchar com o número de caracteres informado.
    * @author Brunoggdev
    */
    public function varchar(string $coluna, int $charsNumber): self
    {
        $this->sql .= "$coluna VARCHAR($charsNumber), ";
        return $this;
    }

    /**
    * Atalho para varchar com 255 caracteres.
    * @author Brunoggdev
    */
    public function string(string $coluna): self
    {
        return $this->varchar($coluna, 255);
    }

    /**
    * Adiciona uma coluna do tipo text.
    * @author Brunoggdev
    */
    public function text(string $coluna): self
    {
        $this->sql .= "$coluna TEXT, ";
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo boolean.
    * @author Brunoggdev
    */
    public function boolean(string $coluna): self
    {
        $this->sql .= "$coluna BOOLEAN, ";
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo datetime
    * @author Brunoggdev
    */
    public function datetime(string $coluna): self
    {
        $this->sql .= "$coluna DATETIME, ";
        return $this;
    }
    
    /**
    * Adiciona uma coluna do tipo date
    * @author Brunoggdev
    */
    public function date(string $coluna): self
    {
        $this->sql .= "$coluna DATE, ";
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo time
    * @author Brunoggdev
    */
    public function time(string $coluna): self
    {
        $this->sql .= "$coluna TIME, ";
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo float.
    * @author Brunoggdev
    */
    public function float(string $coluna, int $totalDigits, int $decimalDigits): self
    {
        $this->sql .= "$coluna FLOAT($totalDigits, $decimalDigits), ";
        return $this;
    }

    /**
    * Adiciona uma coluna do tipo json (nem todos os tipos de sql suportam isso).
    * @author Brunoggdev
    */
    public function json(string $coluna): self
    {
        $this->sql .= "$coluna JSON, ";
        return $this;
    }

    /**
    * Adiciona uma chave estrangeira da coluna desejada para a tabela e coluna especificadas 
    * (lembre-se de usar este metodo apenas no final da sql).
    * @author Brunoggdev
    */
    public function foreignKey(string $coluna, string $tabelaRef, string $colunaRef): self
    {
        $this->sql .= "FOREIGN KEY ($coluna) REFERENCES $tabelaRef($colunaRef)";
        return $this;
    }

    public function __toString(): string
    {
        return  rtrim($this->sql, ", ") . ');';
    }
}