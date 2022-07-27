<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Helper\RequestApi;
use Illuminate\Support\Facades\Cookie;

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

        if ($attempt->success) {
            Cookie::queue('token', $attempt->data->token, 1200);

            return $next($request);
        }
        
        return back()->withErrors($attempt->data);
    }
}
