<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CheckAuthorizationException extends AbstractException
{
    public function __construct($message = '', $code = null)
    {
        if (!$message) {
            $message = trans('exception.403');
        }

        if (!$code) {
            $code = ResponseAlias::HTTP_FORBIDDEN;
        }
        parent::__construct($message, $code);
    }
}
