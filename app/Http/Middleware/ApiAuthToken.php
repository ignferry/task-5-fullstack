<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helper\RequestApi;

class ApiAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $attempt = RequestApi::callAPI('POST', 'login', $request->all(), false);

        if ($attempt['success']) {
            $cookie = cookie('token', $attempt['data']['token'], $httponly=true);

            return $next($request)->withCookie($cookie);
        }
        
        return back()->withErrors($attempt['data']);
    }
}
