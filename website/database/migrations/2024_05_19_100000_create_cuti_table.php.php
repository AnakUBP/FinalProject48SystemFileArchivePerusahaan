<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_cuti_id')->nullable()->constrained('jenis_cuti')->onDelete('set null');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            $table->string('nomor_surat')->unique()->nullable(); // diisi otomatis
            $table->string('file_surat')->nullable(); // file PDF/Word
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};

