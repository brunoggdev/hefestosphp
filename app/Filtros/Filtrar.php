<?php
namespace App\Filtros;

class Filtros
{
    const MAP = [
        'logado' => Logado::class
    ];


    /**
    * Mapeia a chave informada com o array de filtros e,
    * caso um seja encontrado, este será aplicado. 
    * @author Brunoggdev
    */
    public static function filtrar(string $chave):void
    {
        if(!$chave){
            return;
        }

        $filtro = static::MAP[$chave];

        if(!$filtro){
            throw new \Exception("Nenhum filtro encontrado para a chave '{$chave}'.");
        }

        (new $filtro)->aplicar();
    }
}
