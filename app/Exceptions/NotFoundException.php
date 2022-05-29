<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class NotFoundException extends AbstractException
{
    public function __construct($message = '', $code = null)
    {
        if (!$message) {
            $message = trans('exception.404');
        }

        if (!$code) {
            $code = ResponseAlias::HTTP_NOT_FOUND;
        }
        parent::__construct($message, $code);
    }
}
