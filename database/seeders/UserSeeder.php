<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // AKUN FREE
        User::updateOrCreate(
            ['email' => 'free@queuenow.test'],
            [
                'name' => 'Owner Free',
                'password' => Hash::make('password'),
                'business_name' => 'Bisnis Free',
                'business_category' => 'UMKM',
                'phone' => '0811111111',
                'subscription_type' => 'free',
            ]
        );

        // AKUN PRO
        User::updateOrCreate(
            ['email' => 'pro@queuenow.test'],
            [
                'name' => 'Owner Pro',
                'password' => Hash::make('password'),
                'business_name' => 'Bisnis Pro',
                'business_category' => 'Restoran',
                'phone' => '0822222222',
                'subscription_type' => 'pro',
            ]
        );
    }
}
