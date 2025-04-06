<?php

namespace Hefestos\Core;

use Hefestos\Database\Database;
use Hefestos\Ferramentas\Validador;
use Hefestos\Rotas\Requisicao;
use InvalidArgumentException;

/**
 * @property-read Validador $validador
 * @property-read Requisicao $requisicao
 * @property-read Database $db
 * @author Brunoggdev
 */
abstract class Controller
{
    /**
     * "Lazy loading" para as propriedades do controller
     * @author Brunoggdev
     */
    public function __get($nome)
    {
        return match ($nome) {
            'requisicao' => Requisicao::instancia(),
            'validador' => Validador::instancia(),
            'db' => Database::instancia(),
            default => throw new InvalidArgumentException("Propriedade '$nome' não encontrada no Controller.")
        };
    }


    /**
     * Retorna parametros enviados por get já higienizados.
     * @author Brunoggdev
     */
    public function dadosGet(null|string|array $index = null, $higienizar = true): mixed
    {
        return Requisicao::dadosGet($index, $higienizar);
    }


    /**
     * Retorna parametros enviados por post já higienizados.
     * @param array|string|null $index Index para resgatar do $_POST.
     * @author Brunoggdev
     */
    public function dadosPost(null|string|array $index = null, $higienizar = true): mixed
    {
        return Requisicao::dadosPost($index, $higienizar);
    }


    /**
     * Atalho para validar os dados recebidos pela requisição GET
     * @author Brunoggdev
     */
    public function validarDadosGet(array $regras, array $mensagens = []): bool
    {
        return Validador::instancia()->validar(
            Requisicao::dadosGet(),
            $regras,
            $mensagens
        );
    }


    /**
     * Atalho para validar os dados recebidos pela requisição POST
     * @author Brunoggdev
     */
    public function validarDadosPost(array $regras, array $mensagens = []): bool
    {
        return Validador::instancia()->validar(
            Requisicao::dadosPost(),
            $regras,
            $mensagens
        );
    }
}
