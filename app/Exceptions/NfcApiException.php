<?php

namespace App\Exceptions;

use \Exception;
use Illuminate\Http\JsonResponse;

abstract Class NfcApiException extends Exception
{
    public function render(): JsonResponse
    {
        return new JsonResponse(
            data : [
                'error' => $this->getMessage()
            ],
            status: $this->getCode(),
        );
    }
}
