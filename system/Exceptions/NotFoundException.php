<?php

namespace Hefestos\Exceptions;

/**
 * Indica casos onde o recurso solicitado não foi encontrado
 * 
 * **Código HTTP 404**
 * @author Bruno Gomes
 */
class NotFoundException extends HttpException
{
    protected const CODIGO = 404;
    protected const REASON_PHRASE = 'Not Found';
}
