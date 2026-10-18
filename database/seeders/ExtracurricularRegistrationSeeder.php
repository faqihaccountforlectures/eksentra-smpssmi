<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassEnrollment;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExtracurricularRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mendaftarkan setiap siswa ke 1 atau 2 ekskul secara acak untuk TA 2025/2026 dan TA 2026/2027.
     * Semua pendaftaran disetujui (status = 'approved').
     */
    public function run(): void
    {
        $year2025 = AcademicYear::where('year_name', '2025/2026')->first();
        $year2026 = AcademicYear::where('year_name', '2026/2027')->first();

        $extracurriculars = Extracurricular::all();

        if ($extracurriculars->isEmpty() || !$year2025 || !$year2026) {
            return;
        }

        $ekskulIds = $extracurriculars->pluck('id')->toArray();

        // 1. Pendaftaran Siswa untuk Tahun Ajaran 2025/2026
        $enrollments2025 = ClassEnrollment::where('academic_year_id', $year2025->id)->get();

        foreach ($enrollments2025 as $index => $enrollment) {
            // Tentukan jumlah ekskul yang diikuti: 1 atau 2 (bergantian atau acak terkontrol)
            $count = ($index % 3 === 0) ? 2 : 1;
            
            // Pilih ID ekskul secara konsisten menggunakan modulo agar data tidak duplikat
            $chosenKeys = [$index % count($ekskulIds)];
            if ($count === 2) {
                $chosenKeys[] = ($index + 3) % count($ekskulIds);
            }

            foreach ($chosenKeys as $key) {
                $ekskulId = $ekskulIds[$key];
                $createdDate = Carbon::create(2025, 7, 10 + ($index % 15), 9, 0, 0);

                ExtracurricularRegistration::updateOrCreate(
                    [
                        'student_id' => $enrollment->student_id,
                        'extracurricular_id' => $ekskulId,
                        'academic_year_id' => $year2025->id,
                    ],
                    [
                        'status' => 'approved',
                        'created_at' => $createdDate,
                        'updated_at' => $createdDate->copy()->addDays(2),
                    ]
                );
            }
        }

        // 2. Pendaftaran Siswa untuk Tahun Ajaran 2026/2027 (Tahun Berjalan)
        $enrollments2026 = ClassEnrollment::where('academic_year_id', $year2026->id)
            ->where('status', 'aktif')
            ->get();

        foreach ($enrollments2026 as $index => $enrollment) {
            // Tentukan jumlah ekskul: 1 atau 2
            $count = ($index % 2 === 0) ? 2 : 1;

            $chosenKeys = [($index + 1) % count($ekskulIds)];
            if ($count === 2) {
                $chosenKeys[] = ($index + 4) % count($ekskulIds);
            }

            foreach ($chosenKeys as $key) {
                $ekskulId = $ekskulIds[$key];
                $createdDate = Carbon::create(2026, 7, 12 + ($index % 12), 10, 0, 0);

                ExtracurricularRegistration::updateOrCreate(
                    [
                        'student_id' => $enrollment->student_id,
                        'extracurricular_id' => $ekskulId,
                        'academic_year_id' => $year2026->id,
                    ],
                    [
                        'status' => 'approved',
                        'created_at' => $createdDate,
                        'updated_at' => $createdDate->copy()->addDays(2),
                    ]
                );
            }
        }
    }
}
