<?php

namespace Hefestos\Core;

use Hefestos\Rotas\Requisicao;

abstract class Controller
{
    protected Requisicao $requisicao;

    public function __construct(?Requisicao $requisicao = null) {
        $this->requisicao = $requisicao ?? Requisicao::instancia();
    }


    /**
    * Retorna parametros enviados por get já higienizados.
    * @author Brunoggdev
    */
    public function dadosGet(null|string|array $index = null, $higienizar = true):mixed
    {
        return $this->requisicao->dadosGet($index, $higienizar);
    }


    /**
    * Retorna parametros enviados por post já higienizados.
    * @param array|string|null $index Index para resgatar do $_POST.
    * @author Brunoggdev
    */
    public function dadosPost(null|string|array $index = null, $higienizar = true):mixed
    {
       return $this->requisicao->dadosPost($index, $higienizar);
    }
}
