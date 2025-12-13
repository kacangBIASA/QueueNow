<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && (!$user->business_name || !$user->phone || !$user->business_category)) {
            if (!$request->routeIs('onboarding') && !$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding');
            }
        }

        return $next($request);
    }
}
