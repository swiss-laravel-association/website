<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NfcUserCredentialException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NfcApiController extends Controller
{

    public function clientConnect(Request $request)
    {
        return response()->json([]);
    }

    /**
     * @throws NfcUserCredentialException
     */
    public function memberSignIn(Request $request)
    {
        try{
            if(! ($request->has('uuid') && $request->has('signature'))) throw new Exception();
            $user = User::whereUuid($request->get('uuid'))->firstOrFail();
            if($user->checkSignature($request->get('signature'))){
                throw new Exception();
            }
        }catch (Exception){
            throw new NfcUserCredentialException();
        }

        // all shiny! Write the user in
        return response()->json([], 201);
    }

}
