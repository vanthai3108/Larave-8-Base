<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UnprocessableEntityException extends AbstractException
{
    public function __construct($message = '', $code = null)
    {
        if (!$message) {
            $message = trans('exception.422');
        }
        if (!$code) {
            $code = ResponseAlias::HTTP_UNPROCESSABLE_ENTITY;
        }
        parent::__construct($message, $code);
    }
}
