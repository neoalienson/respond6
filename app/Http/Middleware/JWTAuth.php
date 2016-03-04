<?php

namespace App\Http\Middleware;

use Closure;
use App\Respond\Libraries\Utilities;

class JWTAuth
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
        $token = Utilities::ValidateJWTToken();
        
        // if token is NULL return 401
        if($token == NULL) {
            return response('Unauthorized.', 401);
        }
    
        // merge the userId and siteId into the request
        $request.merge(array('userId' => $token->UserId, 'siteId' => $token->SiteId));
    
        // continue
        return $next($request);
    }
}

