<?php

namespace Hefestos\Exceptions;

/**
 * Indica casos onde o usuário não tem permissão para realizar uma operação
 * 
 * **Código HTTP 403**
 * @author Bruno Gomes
 */
class ForbiddenException extends HttpException
{
    protected const CODIGO = 403;
    protected const REASON_PHRASE = 'Forbidden';
}
