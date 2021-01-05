<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;

use Closure;

class AuthKey
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
        //with request-> not work in docker
        //gatau laravel jadi gabisa debugnya cari solusinya aja deh
        // $token = $request->header('INIDOC_KEY');
        // $token2 = $_SERVER['test'];
        // Log::info('Showing token: '.$token);
        // Log::info('Showing token: '.$token2);

        //resolve
        if(!isset($_SERVER['HTTP_X_INIDOC_KEY'])){  
            return response()->json([
                        'error' => 'Set auth header'
                    ], 401);
        }  
  
        if($_SERVER['HTTP_X_INIDOC_KEY'] != 'cf2d4b4d41435f8bae44e1d903cd430c'){  
            return response()->json([
                        'message' => 'App Key Not Found!'
                    ], 401);
        }
        // if ($token != 'cf2d4b4d41435f8bae44e1d903cd430c'){
        //     return response()->json([
        //         'message' => 'App Key Not Found!'
        //     ], 401);
        // }
        return $next($request);
    }
}
