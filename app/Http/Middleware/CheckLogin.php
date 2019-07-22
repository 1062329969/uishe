<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
        if (!$this->auth->user()) {
            if ($request->ajax()) {
                return response()->error(500, '请登录');
            } else {
                return redirect(route('login', ['r' => $request->url()]));
            }
        }
        return $next($request);
    }
}
