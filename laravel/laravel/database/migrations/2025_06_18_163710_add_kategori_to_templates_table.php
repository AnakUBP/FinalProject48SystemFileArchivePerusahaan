<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan Schema::table() untuk mengubah tabel yang sudah ada
        Schema::table('templates', function (Blueprint $table) {
            // Menambahkan kolom 'kategori' setelah kolom 'nama_template'
            // Dibuat nullable() agar data lama tidak error
            $table->string('kategori')->nullable()->after('nama_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            // Logika untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('kategori');
        });
    }
};
