<?php

namespace App\Http\Middleware;

use App\Models\ServiceSecret;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NfcAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(! $this->checkSignature()){
            abort(403);
        }
        return $next($request);
    }

    private function checkSignature(): bool
    {
        return true;
    }
}
