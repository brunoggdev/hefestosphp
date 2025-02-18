<?php

namespace Hefestos\Core;

interface Entidade {
    /**
     * Esse método deve retornar um array associativo onde na chave deve ter o nome da
     * coluna no banco de dados e no seu valor o desejado em tipo primitivo (string, int etc).
    */
    public function paraArray(): array;
}