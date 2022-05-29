<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class QueryException extends AbstractException
{
    public function __construct($message = '', $code = null)
    {
        if (!$message) {
            $message = trans('exception.query_error');
        }

        if (!$code) {
            $code = ResponseAlias::HTTP_BAD_REQUEST;
        }
        parent::__construct($message, $code);
    }
}
