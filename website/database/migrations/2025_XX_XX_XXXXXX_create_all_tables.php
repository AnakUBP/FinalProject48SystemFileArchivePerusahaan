<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'hrd', 'karyawan']);
            $table->timestamps();
        });

        // Jenis Cuti
        Schema::create('jenis_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Templates
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');
            $table->string('kategori', 100);
            $table->string('file_path');
            $table->timestamps();
        });

        // Pengajuan Cuti
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_cuti_id')->constrained('jenis_cuti')->onDelete('set null');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            $table->string('nomor_surat')->unique()->nullable(); // diisi otomatis
            $table->string('file_surat')->nullable(); // file PDF/Word
            $table->timestamps();
        });

        // Riwayat Approval
        Schema::create('approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuti_id')->constrained('cuti')->onDelete('cascade');
            $table->foreignId('approved_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['disetujui', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamp('approved_at')->useCurrent();
        });

        // Arsip Surat Cuti
        Schema::create('arsip_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuti_id')->constrained('cuti')->onDelete('cascade');
            $table->string('nomor_surat')->unique();
            $table->string('file_surat'); // file final
            $table->timestamp('tanggal_arsip')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_cuti');
        Schema::dropIfExists('approval_logs');
        Schema::dropIfExists('cuti');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('jenis_cuti');
        Schema::dropIfExists('users');
    }
};
