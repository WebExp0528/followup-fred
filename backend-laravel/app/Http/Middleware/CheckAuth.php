<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\{AuthFailException,GeneralException};
class CheckAuth
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
        if(!$request->input('email')){
            throw new GeneralException("Invalid request","Request need to contain email address");
        }
        if (!UserService::getService()->checkUser($request->all())) {
            throw new AuthFailException();
        }
        return $next($request);
    }
}
