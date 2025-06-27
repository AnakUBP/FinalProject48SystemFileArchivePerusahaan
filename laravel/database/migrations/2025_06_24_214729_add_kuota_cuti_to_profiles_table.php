<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Tambahkan kolom untuk sisa kuota cuti setelah kolom jabatan
            // Angka 12 adalah contoh, Anda bisa sesuaikan dengan kebijakan perusahaan.
            $table->integer('sisa_kuota_cuti')->default(12)->after('jabatan');
        });
    }
};