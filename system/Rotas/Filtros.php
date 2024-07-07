<?php

namespace Hefestos\Rotas;

class Filtros
{

    private array $filtros;

    public function __construct()
    {
        $this->filtros = config('filtros');
    }

    
    /**
    * Mapeia a chave informada com o array de filtros e,
    * caso um seja encontrado, este será aplicado. 
    * @author Brunoggdev
    */
    public function filtrar(string $chave):void
    {
        if(!$chave){
            return;
        }

        try{
            $filtro = $this->filtros[$chave];
        }catch(\Throwable){
            throw new \Exception("Nenhum filtro encontrado para a chave '{$chave}'.");
        }
 
        $filtros = (array) $filtro;

        foreach ($filtros as $filtro) {
            if ( (new $filtro)->aplicar() instanceof Redirecionar){
                exit;
            }
        }
    } 
}
