<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user->is_profile_complete) {
            return redirect('profile.edit')->with('warning', 'Please complete filling in your information');
        }
        if ($user->is_super_admin && !$user->restaurant->is_profile_complete) {
            return redirect()->route('settings.restaurant.edit')->with('warning', 'Please complete filling in your Restaurant information');
        }
        return $next($request);
    }
}
