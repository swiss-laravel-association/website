<?php

namespace App\Exceptions;

use Exception;

class NfcUserCredentialException extends NfcApiException
{
    protected $code = 403;
    protected $message = '';

    public function __construct()
    {
        $this->message = trans('api.nfc.errors.user_credentials');
    }
}
