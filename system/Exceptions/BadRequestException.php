<?php

namespace Hefestos\Exceptions;

/**
 * Indica casos onde o usuário enviou uma requisição inválida
 * 
 * **Código HTTP 400**
 * @author Bruno Gomes
 */
class BadRequestException extends HttpException
{
    protected const CODIGO = 500;
    protected const REASON_PHRASE = 'Bad Request';

    protected array $erros = [];

    public static function comErros(array $erros): static
    {
        $exception = new static('Bad Request', 400);
        $exception->erros = $erros;

        return $exception;
    }

    public function erros(): array
    {
        return $this->erros;
    }
}
