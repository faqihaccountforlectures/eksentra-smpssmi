<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            
            // Relasi ke pendaftaran ekstrakurikuler siswa
            $table->foreignId('registration_id')->constrained('extracurricular_registrations')->onDelete('cascade');
            
            // Relasi ke tahun ajaran
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            
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

        // Migrasi data nilai sementara jika ada di extracurricular_registrations
        $activeYear = DB::table('academic_years')->where('is_active', true)->first() 
            ?? DB::table('academic_years')->first();

        if ($activeYear) {
            $existing = DB::table('extracurricular_registrations')
                ->whereNotNull('grade')
                ->get();

            foreach ($existing as $row) {
                DB::table('extracurricular_grades')->insert([
                    'registration_id' => $row->id,
                    'academic_year_id' => $activeYear->id,
                    'semester' => $activeYear->semester ?? 'ganjil',
                    'grade' => $row->grade,
                    'score' => $row->score,
                    'notes' => $row->notes,
                    'created_at' => $row->graded_at ?? now(),
                    'updated_at' => $row->graded_at ?? now(),
                ]);
            }
        }

        // Hapus kolom sementara dari extracurricular_registrations
        if (Schema::hasColumn('extracurricular_registrations', 'grade')) {
            Schema::table('extracurricular_registrations', function (Blueprint $table) {
                $table->dropColumn(['grade', 'score', 'notes', 'graded_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_grades');
    }
};
