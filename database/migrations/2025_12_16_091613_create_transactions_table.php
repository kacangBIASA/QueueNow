<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('order_id')->unique();
            $table->unsignedInteger('gross_amount');

            $table->string('status')->default('created'); // created|pending|settlement|capture|deny|cancel|expire|refund|chargeback
            $table->string('payment_type')->nullable();

            $table->string('snap_token')->nullable();
            $table->json('payload')->nullable(); // simpan notif raw midtrans

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
