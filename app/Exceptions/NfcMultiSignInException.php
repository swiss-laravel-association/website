<?php

namespace App\Exceptions;

use Exception;

class NfcMultiSignInException extends NfcApiException
{
    protected $code = 403;
    protected $message = '';

    public function __construct()
    {
        $this->message = trans('api.nfc.errors.multi_sign_in');
    }
}
