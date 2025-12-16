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

            // nomor & tanggal antrean
            $table->date('queue_date');
            $table->unsignedInteger('number');

            // sumber antrean
            $table->enum('source', ['online', 'onsite'])->default('online');

            // status antrean
            $table->enum('status', ['waiting', 'called', 'skipped', 'finished'])
                ->default('waiting');

            // timestamps proses antrean
            $table->timestamp('taken_at')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            // constraint & index
            $table->unique(['branch_id', 'queue_date', 'number']);
            $table->index(['branch_id', 'queue_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
