<?php

namespace Hefestos\Ferramentas;

class Cronometro
{
    protected float $inicio;
    protected float $fim;

    /**
     * Inicia a contagem do cronometro
    */
    public function iniciar():void
    {
        $this->inicio = microtime(true);
    }

    /**
     * Para a contagem do cronometro
    */
    public function parar():void
    {
        $this->fim = microtime(true);
    }

    /**
     * Retorna o tempo corrido no cronometro em segundos (com 5 casas decimais por padrão). Retorna nulo se não tiver sido iniciado ou parado.
    */
    public function tempoCorrido(int $casas_decimais = 5):?float
    {
        if (!$this->inicio || !$this->fim) {
            return null;
        } 

        return number_format($this->fim - $this->inicio, $casas_decimais);
    }
}
