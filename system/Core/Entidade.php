<?php

namespace Hefestos\Core;

interface Entidade {

    /**
     * Deve retornar a string com o nome da coluna usada como chave primária na tabela desse recurso. 
     * Normalmente vai ser "id" mas aqui você tem liberdade para configurar como desejar.
    */
    public function chavePrimaria(): string;


    /**
     * Esse método deve retornar um array associativo onde na chave deve ter o nome da
     * coluna no banco de dados e no seu valor o desejado em tipo primitivo (string, int etc).
    */
    public function paraArray(): array;
}