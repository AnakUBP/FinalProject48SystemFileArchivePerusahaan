<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuti_id')->constrained('cuti')->onDelete('cascade');
            $table->foreignId('approved_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['disetujui', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamp('approved_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_logs');
    }
};
