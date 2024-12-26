<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NfcApiController extends Controller
{

    public function clientConnect()
    {

    }

    public function memberSignIn()
    {
        $uuid = '1a24282c-dcbf-4502-9ae2-40c6cadf3550';
        $userSecret = 'C7HRERb6DENA242y3P7ydHtQvcGazlQOYpbLMyGlMYYhpeZHeFIDDulj1KnNUDaY';
        $signature = base64_encode(Hash::make(Crypt::encrypt($uuid . '.' . $userSecret)));
//        $signature = Encrypter::

        return response()->json(compact('uuid', 'signature'), 201);
    }

}
