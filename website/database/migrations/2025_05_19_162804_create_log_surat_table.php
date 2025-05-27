<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('log_surat', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->foreignId('cuti_id')->nullable()->constrained('cuti')->onDelete('set null');
            $table->string('nomor_surat')->nullable();
            $table->string('kategori', 100)->nullable();
            $table->string('status', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('waktu')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_surat');
    }
};
