<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
    Schema::create('arsip_cuti', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cuti_id')->constrained('cuti')->onDelete('cascade');
    $table->string('nomor_surat')->unique();
    $table->string('file_surat'); // harus sama dengan nama di seeder
    $table->timestamp('tanggal_arsip')->useCurrent();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_cuti');
    }
};
