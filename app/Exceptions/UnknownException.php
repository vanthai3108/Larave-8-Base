<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UnknownException extends AbstractException
{
    public function __construct($message = '', $code = null)
    {
        if (!$message) {
            $message = trans('exception.bad_request');
        }
        if (!$code) {
            $code = ResponseAlias::HTTP_BAD_REQUEST;
        }
        parent::__construct($message, $code);
    }
}
