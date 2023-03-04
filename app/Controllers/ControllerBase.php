<?php

class ControllerBase
{
    /**
    * Retorna parametros enviados por post já higienizados.
    * @param array|string|null $index Index para resgatar do $_POST.
    * @author Brunoggdev
    */
    public function requisicaoPost(?mixed $index = null, $higienizar = true):mixed
    {
        $post = $_POST[$index];

        if (is_array($post)) {
            return higienizaArray($post);
        }

        return strip_tags($post);
    }


    /**
    * Retorna parametros enviados por get já higienizados.
    * @author Brunoggdev
    */
    public function requisicaoGet($index = null, $higienizar = true):mixed
    {
        $get = $_GET[$index];

        if (is_array($get)) {
            return higienizaArray($get);
        }

        return strip_tags($get);
    }
}
