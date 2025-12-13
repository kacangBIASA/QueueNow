<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // data owner bisnis
            $table->string('business_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('business_category')->nullable();

            // subscription
            $table->enum('subscription_type', ['free', 'pro'])->default('free');
            $table->timestamp('subscription_expires_at')->nullable();

            // google login
            $table->string('google_id')->nullable()->unique();
            $table->string('avatar')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'phone',
                'business_category',
                'subscription_type',
                'subscription_expires_at',
                'google_id',
                'avatar',
            ]);
        });
    }
};
