<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ExtracurricularGrade;
use App\Models\ExtracurricularRegistration;
use Illuminate\Database\Seeder;

class ExtracurricularGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data nilai rapor ekstrakurikuler untuk:
     * - TA 2025/2026: Semester Ganjil & Genap (lengkap).
     * - TA 2026/2027: Evaluasi Semester Ganjil.
     */
    public function run(): void
    {
        $year2025 = AcademicYear::where('year_name', '2025/2026')->first();
        $year2026 = AcademicYear::where('year_name', '2026/2027')->first();

        $sampleEvaluations = [
            ['grade' => 'A', 'score' => 95, 'notes' => 'Sangat aktif, berdedikasi tinggi, dan menunjukkan penguasaan teknik yang luar biasa.'],
            ['grade' => 'A', 'score' => 90, 'notes' => 'Menunjukkan kepemimpinan, kedisiplinan, dan kerja sama tim yang sangat baik.'],
            ['grade' => 'A', 'score' => 88, 'notes' => 'Konsisten hadir tepat waktu dan memiliki semangat belajar yang tinggi.'],
            ['grade' => 'B', 'score' => 85, 'notes' => 'Mengikuti setiap sesi latihan dengan tekun dan menunjukkan kemajuan yang memuaskan.'],
            ['grade' => 'B', 'score' => 82, 'notes' => 'Kemampuan praktik cukup baik, perlu terus diasah dan ditingkatkan lagi rasa percaya diri.'],
            ['grade' => 'B', 'score' => 80, 'notes' => 'Kehadiran dan keaktifan baik, pertahankan konsistensi dalam kegiatan kelompok.'],
        ];

        // 1. Nilai untuk TA 2025/2026 (Semester Ganjil dan Genap)
        if ($year2025) {
            $registrations2025 = ExtracurricularRegistration::with('extracurricular')->where('academic_year_id', $year2025->id)->get();

            foreach ($registrations2025 as $index => $reg) {
                // Semester Ganjil
                $evalGanjil = $sampleEvaluations[$index % count($sampleEvaluations)];
                ExtracurricularGrade::updateOrCreate(
                    [
                        'registration_id' => $reg->id,
                        'academic_year_id' => $year2025->id,
                        'semester' => 'ganjil',
                    ],
                    [
                        'coach_id' => $reg->extracurricular->coach_id ?? null,
                        'grade' => $evalGanjil['grade'],
                        'score' => $evalGanjil['score'],
                        'notes' => $evalGanjil['notes'],
                    ]
                );

                // Semester Genap
                $evalGenap = $sampleEvaluations[($index + 2) % count($sampleEvaluations)];
                ExtracurricularGrade::updateOrCreate(
                    [
                        'registration_id' => $reg->id,
                        'academic_year_id' => $year2025->id,
                        'semester' => 'genap',
                    ],
                    [
                        'coach_id' => $reg->extracurricular->coach_id ?? null,
                        'grade' => $evalGenap['grade'],
                        'score' => $evalGenap['score'],
                        'notes' => $evalGenap['notes'],
                    ]
                );
            }
        }

        // 2. Nilai untuk TA 2026/2027 (Evaluasi Berjalan Semester Ganjil)
        if ($year2026) {
            $registrations2026 = ExtracurricularRegistration::with('extracurricular')->where('academic_year_id', $year2026->id)->get();

            foreach ($registrations2026 as $index => $reg) {
                $eval = $sampleEvaluations[($index + 1) % count($sampleEvaluations)];
                ExtracurricularGrade::updateOrCreate(
                    [
                        'registration_id' => $reg->id,
                        'academic_year_id' => $year2026->id,
                        'semester' => 'ganjil',
                    ],
                    [
                        'coach_id' => $reg->extracurricular->coach_id ?? null,
                        'grade' => $eval['grade'],
                        'score' => $eval['score'],
                        'notes' => $eval['notes'],
                    ]
                );
            }
        }
    }
}
