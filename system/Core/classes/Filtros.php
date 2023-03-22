<?php
namespace System\Core\Classes;

class Filtros
{

    private array $filtros;

    public function __construct()
    {
        $this->filtros = require_once pasta_app('Filtros/filtros.php');
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
 
        (new $filtro)->aplicar();
    }
}
