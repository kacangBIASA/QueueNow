<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        // Kalau sudah lengkap, jangan balik onboarding
        if ($this->isProfileComplete($user)) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'business_category' => ['required', 'string', 'max:100'],
        ]);

        $user = Auth::user();
        $user->update([
            'business_name' => $request->business_name,
            'phone' => $request->phone,
            'business_category' => $request->business_category,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil bisnis berhasil dilengkapi.');
    }

    private function isProfileComplete($user): bool
    {
        return !empty($user->business_name) && !empty($user->phone) && !empty($user->business_category);
    }
}
