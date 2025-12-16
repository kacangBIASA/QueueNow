<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePro
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Pro lifetime / atau pakai expires kalau kamu aktifkan
        $isPro = $user && $user->subscription_type === 'pro';

        // Jika pakai subscription_expires_at, aktifkan ini:
        // $isPro = $isPro && (is_null($user->subscription_expires_at) || now()->lt($user->subscription_expires_at));

        if (!$isPro) {
            return redirect()->route('pricing')->with('error', 'Fitur ini hanya untuk akun Pro.');
        }

        return $next($request);
    }
}
