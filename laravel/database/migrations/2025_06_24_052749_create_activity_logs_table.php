<?php
// database/migrations/xxxx_create_activity_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Siapa yang melakukan aksi? (Bisa null jika aksi oleh sistem)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            // Aksi apa yang dilakukan? (e.g., 'membuat_pengajuan', 'menyetujui_cuti')
            $table->string('action');
            // Deskripsi yang mudah dibaca manusia
            $table->text('description');
            // Kolom untuk polymorphic relationship
            $table->morphs('loggable'); // Akan membuat loggable_id dan loggable_type
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
