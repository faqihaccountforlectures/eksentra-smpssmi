<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel extracurricular_grades untuk menyimpan nilai ekskul per tahun ajaran dan per semester (Ganjil & Genap).
     */
    public function up(): void
    {
        Schema::create('extracurricular_grades', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke pendaftaran ekstrakurikuler siswa (restrict agar histori nilai tidak terhapus jika siswa keluar)
            $table->foreignId('registration_id')->constrained('extracurricular_registrations')->onDelete('restrict');
            
            // Relasi ke tahun ajaran
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');

            // Relasi ke pembina/coach yang memberikan penilaian (mencegah rusaknya histori nilai saat rolling pembina)
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Semester: Ganjil atau Genap
            $table->enum('semester', ['ganjil', 'genap']);
            
            // Nilai & Evaluasi
            $table->string('grade', 10)->nullable()->comment('Predikat nilai: A, B, C, D');
            $table->unsignedInteger('score')->nullable()->comment('Nilai angka: 0 - 100');
            $table->text('notes')->nullable()->comment('Catatan pembina / evaluasi perkembangan');
            
            $table->timestamps();

            // Memastikan satu pendaftaran hanya memiliki 1 nilai per tahun ajaran dan semester
            $table->unique(['registration_id', 'academic_year_id', 'semester'], 'ext_grades_reg_year_sem_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_grades');
    }
};
