<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForeignerMiddleware
{
    public function handle($request, Closure $next) {

        if(!$request->headers->has('authorization')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            $passport = trim(str_replace("Bearer", '', $request->header('authorization')));
            
            if(env('FOREIGNER_PASSPORT') != $passport) {
                //TO DO:: Add validation to passport e.g. specific date only , once used cannot access anymore... 
               return response()->json(['error' => 'Cannot enter the port.'], 403);
            }

            return $next($request);    
        }        
    }
}
