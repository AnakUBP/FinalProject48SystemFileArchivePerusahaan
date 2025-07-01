<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jabatan')->nullable();
            $table->enum('jenis_kelamin', ['pria', 'wanita']);
            $table->string('foto')->nullable(); // path foto profil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
