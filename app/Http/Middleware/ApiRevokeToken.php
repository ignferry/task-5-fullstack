<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helper\RequestApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class ApiRevokeToken
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
        Http::withToken(request()->cookie('token'))->post(env('API_URL') . 'logout');

        $cookie = Cookie::forget('token');

        return $next($request)->withCookie($cookie);
    }
}
