<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NfcMultiSignInException;
use App\Exceptions\NfcUserCredentialException;
use App\Http\Controllers\Controller;
use App\Models\Event;
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
        /** @var Event $event */
        $event = $request->get('event');
        $event->load('users');

        return response()->json([
            'sign_in_count' => $event->users()->count()
        ]);
    }

    /**
     * @throws NfcUserCredentialException
     * @throws NfcMultiSignInException
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

        /** @var Event $event */
        $event = $request->get('event');
        $event->load('users');

        if(! $event->users->filter(fn(User $u) => $u->id === $user->id)->isEmpty()){
            throw new NfcMultiSignInException();
        }

            // add user to event
        $event->users()->save($user);;

        // all shiny! Write the user in
        return response()->json(['some shiny' => 'data maybe?'], 201);
    }

}
