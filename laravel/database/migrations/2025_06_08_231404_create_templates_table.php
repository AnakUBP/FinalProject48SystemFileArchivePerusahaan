<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_template');
            $table->string('file_path')->nullable();
            $table->string('versi', 20)->default('1.0.0'); // Tambahan kolom versi
            $table->boolean('active')->default(true); // Tambahan status aktif
            $table->timestamps();

            $table->index('active'); // Index untuk pencarian berdasarkan status
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
