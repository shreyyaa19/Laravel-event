<?php

namespace app\Http\Middleware;

use App\Models\Admin;
use Closure;

class FirstRunMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if (Admin::scope()->count() === 0 && !($request->route()->getName() == 'showCreateAdmin') && !($request->route()->getName() == 'postCreateAdmin')) {
            return redirect(route('showCreateAdmin', [
                'first_run' => '1',
            ]));
        } elseif (Admin::scope()->count() === 1 && ($request->route()->getName() == 'showSelectAdmin')) {
            return redirect(route('showAdminDashboard', [
                'admin_id' => Admin::scope()->first()->id,
            ]));
        }

        $response = $next($request);

        return $response;
    }
}
