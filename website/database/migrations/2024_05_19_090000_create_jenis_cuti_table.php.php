<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cuti')->unique(); // ✅ tambahkan ini
            $table->string('nama_cuti');           // ✅ ubah dari 'nama' ke 'nama_cuti' biar sesuai dengan seeder
            $table->text('keterangan')->nullable();
           $table->foreignId('templates_id')->nullable()->constrained('templates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_cuti');
    }
};
