<?php

namespace Hefestos\Exceptions;

/**
 * Indica casos onde o usuário não está autenticado para a requisição
 * 
 * **Código HTTP 401**
 * @author Bruno Gomes
 */
class UnauthorizedException extends HttpException
{
    protected const CODIGO = 401;
    protected const REASON_PHRASE = 'Unauthorized';
}
