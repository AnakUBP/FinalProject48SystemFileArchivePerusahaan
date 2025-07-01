<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')
                ->nullable()
                ->constrained('templates')
                ->onDelete('set null');
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('template_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_cuti');
    }
};
