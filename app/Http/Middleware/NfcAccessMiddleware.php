<?php

namespace App\Http\Middleware;

use App\Exceptions\NfcClientCodeException;
use App\Models\Event;
use App\Models\ServiceSecret;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NfcAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws NfcClientCodeException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(! $request->hasHeader('x-client-signature')) throw new NfcClientCodeException();
        $event = Event::getBySignature($request->header('x-client-signature'));
        if( ! ($event instanceof Event))  throw new NfcClientCodeException();
        $request->merge(['event' => $event]);
        return $next($request);
    }
}
