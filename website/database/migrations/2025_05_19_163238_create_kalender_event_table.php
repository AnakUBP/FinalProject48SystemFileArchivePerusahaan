<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kalender_event', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('cuti_id')->nullable()->constrained('cuti')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kalender_event');
    }
};
