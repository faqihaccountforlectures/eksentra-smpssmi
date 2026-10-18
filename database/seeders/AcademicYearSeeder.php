<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data tahun ajaran 2025/2026 (selesai/nonaktif) dan 2026/2027 (aktif).
     */
    public function run(): void
    {
        $academicYears = [
            [
                'year_name' => '2025/2026',
                'is_active' => false,
            ],
            [
                'year_name' => '2026/2027',
                'is_active' => true,
            ],
        ];

        foreach ($academicYears as $year) {
            AcademicYear::updateOrCreate(
                ['year_name' => $year['year_name']],
                ['is_active' => $year['is_active']]
            );
        }
    }
}
