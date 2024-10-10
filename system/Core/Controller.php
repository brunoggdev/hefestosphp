<?php

namespace Hefestos\Core;

use Hefestos\Ferramentas\Validador;

abstract class Controller
{

    public static ?Validador $validador = null;

    /**
    * Retorna parametros enviados por get já higienizados.
    * @author Brunoggdev
    */
    public function dadosGet(null|string|array $index = null, $higienizar = true):mixed
    {
        return requisicao()->dadosGet($index, $higienizar);
    }


    /**
    * Retorna parametros enviados por post já higienizados.
    * @param array|string|null $index Index para resgatar do $_POST.
    * @author Brunoggdev
    */
    public function dadosPost(null|string|array $index = null, $higienizar = true):mixed
    {
       return requisicao()->dadosPost($index, $higienizar);
    }


    /**
     * Retorna uma instancia do Validador.
     * @author Brunoggdev
     */
    public function validador():Validador
    {
        if (is_null(self::$validador)) {
            self::$validador = new Validador();
        }

        return self::$validador;
    }
}
