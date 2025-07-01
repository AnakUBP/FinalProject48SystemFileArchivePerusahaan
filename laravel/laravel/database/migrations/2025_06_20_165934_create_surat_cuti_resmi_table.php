<?php

// ===================================================================
// FILE 2: database/migrations/xxxx_create_surat_cuti_resmi_table.php
// ===================================================================
// Jalankan: php artisan make:migration create_surat_cuti_resmi_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_cuti_resmi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_cuti_id')->unique()->constrained('pengajuan_cuti')->onDelete('cascade');
            $table->string('nomor_surat')->unique()->nullable(); // Dibuat nullable jika status ditolak
            $table->string('file_hasil_path')->nullable(); // Dibuat nullable jika status ditolak
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->useCurrent();
            $table->text('catatan_approval')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'kadaluwarsa'])->default('diajukan');
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_cuti_resmi');
    }
};
