<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function(&$input) {

            $input = preg_replace("/<style\\b[^>]*>(.*?)<\\/style>/s", "", $input);
            $input = preg_replace("/<script\\b[^>]*>(.*?)<\\/script>/s", "", $input);

            $input = $input;

        });

        $request->merge($input);

        return $next($request); 
    }
}
