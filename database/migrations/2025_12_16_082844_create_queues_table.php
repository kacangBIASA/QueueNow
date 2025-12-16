<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('nomor_antrean');
            $table->timestamp('waktu_diambil');
            $table->timestamp('waktu_dipanggil')->nullable();

            $table->enum('status', ['waiting', 'called', 'done'])
                ->default('waiting');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
