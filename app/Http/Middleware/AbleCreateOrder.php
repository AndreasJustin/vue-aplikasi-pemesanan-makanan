<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
class AbleCreateOrder
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user->role_id != 1 && $user->role_id != 4) {
            return response('Forbidden', 403);
        }
        return $next($request);
    }
}