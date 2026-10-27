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
        Schema::create('coach_profiles', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('full_name', 100); // Nama lengkap pembina
            $table->enum('gender', ['laki-laki', 'perempuan']); // Jenis kelamin
            $table->date('birth_date'); // Tanggal lahir pembina
            $table->string('phone_number', 20)->nullable(); // Nomor telepon
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_profiles');
    }
};
