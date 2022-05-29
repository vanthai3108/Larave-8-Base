<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ServerException extends AbstractException
{
    public function __construct($message = '', $code = null)
    {
        if (!$message) {
            $message = trans('exception.server_error');
        }

        if (!$code) {
            $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
        }
        parent::__construct($message, $code);
    }
}
