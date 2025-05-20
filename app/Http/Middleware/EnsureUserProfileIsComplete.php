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
        $excludedPaths = [
            'profile/edit',
            'settings/restaurant/edit',
            'logout', // optional if you're using web logout route
        ];

        // Check if current path is excluded (no trailing slashes)
        if (in_array(trim($request->path(), '/'), $excludedPaths)) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check for user profile completion
        if (!$user->is_profile_complete) {
            return redirect()->route('profile.edit')->with('warning', 'Please complete your personal information');
        }

        // Check for restaurant profile completion (only for super admin)
        if ($user->is_super_admin && (!$user->restaurant || !$user->restaurant->is_restaurant_profile_complete)) {
            return redirect()->route('settings.restaurant.edit')->with('warning', 'Please complete your restaurant information');
        }

        return $next($request);
    }
}
