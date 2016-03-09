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

        $auth = $request->header('X-AUTH');
        $token = NULL;

        if($auth != NULL) {
          $token = Utilities::ValidateJWTToken($auth);
        }
        else {
          return response('Unauthorized.', 401);
        }

        // merge the userId, siteId and friendlyId into the request
        $request->merge(array('userId' => $token->UserId, 'siteId' => $token->SiteId, 'friendlyId' => $token->FriendlyId));

        // continue
        return $next($request);
    }
}

