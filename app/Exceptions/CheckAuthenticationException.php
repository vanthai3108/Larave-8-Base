<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CheckAuthenticationException extends AbstractException
{
    /**
     * CheckAuthenticationException constructor.
     * @param string $message
     * @param null $code
     */
    public function __construct($message = '', $code = null)
    {
        if (!$message) {
            $message = trans('exception.401');
        }

        if (!$code) {
            $code = ResponseAlias::HTTP_UNAUTHORIZED;
        }
        parent::__construct($message, $code);
    }
}
