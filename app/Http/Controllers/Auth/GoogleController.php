<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $gUser = Socialite::driver('google')->stateless()->user();

        // Prioritas cari by google_id, fallback by email
        $user = User::where('google_id', $gUser->getId())
            ->orWhere('email', $gUser->getEmail())
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $gUser->getName() ?? $gUser->getNickname() ?? 'User',
                'email' => $gUser->getEmail(),
                'google_id' => $gUser->getId(),
                'avatar' => $gUser->getAvatar(),
                'password' => bcrypt(Str::random(32)),
                'subscription_type' => 'free',
            ]);
        } else {
            // sinkronkan google_id/avatar jika belum ada
            $user->update([
                'google_id' => $user->google_id ?? $gUser->getId(),
                'avatar' => $gUser->getAvatar(),
            ]);
        }

        Auth::login($user);

        // Jika user baru via Google belum isi profil bisnis → arahkan ke onboarding (nanti kita buat)
        if (!$user->business_name || !$user->phone || !$user->business_category) {
            return redirect()->route('onboarding');
        }

        return redirect()->route('dashboard');
    }
}
