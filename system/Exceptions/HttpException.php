<?php

namespace Hefestos\Exceptions;

/**
 * Exceção base para todas as exceções HTTP do HefestosPHP,
 * onde o código da exceção é usado para o status HTTP.
 * @author Bruno Gomes
 */
abstract class HttpException extends \RuntimeException
{
    protected const CODIGO = 500;
    protected const REASON_PHRASE = 'Internal Server Error';
    
    public static function para(string $message): static
    {
        return new static($message, static::CODIGO);
    }

    public function getReasonPhrase(): string
    {
        return static::REASON_PHRASE;
    }
}
