<?php

namespace App\Http\Middleware;

use function abort;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

use function flash;
use Illuminate\Http\Request;
use function redirect;
use function response;

class SentinelHasAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  string    $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if ($user = Sentinel::check()) {
            if (Sentinel::getUser()->status == 1) {
                if (! $user->isSuperAdmin()) {
                    if (Sentinel::hasAccess($permission)) {
                        if ($request->ajax() || $request->wantsJson()) {
                            return response('Unauthorized.', 403);
                        }

                        return abort(401);
                    }
                }
            } else {
                flash()->error('Your account is suspend!');
                return redirect()->back()->withInput();
            }
        } else {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
