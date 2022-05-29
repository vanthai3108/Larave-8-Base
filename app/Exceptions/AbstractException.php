<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

abstract class AbstractException extends Exception
{
    protected $code;
    protected $message;

    /**
     * AbstractException constructor.
     * @param $message
     * @param int $code
     */
    public function __construct($message = null, int $code = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->code = $code;
        $this->message = $message ?: trans('exception.server_error');

        parent::__construct($message, $code);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request)
    {
        return response()->json([
            'message' => $this->message,
            'data' => [],
        ], $this->code);
    }

    /**
     * Log an exception.
     */
    public function report()
    {
        Log::emergency($this->message);
    }
}
