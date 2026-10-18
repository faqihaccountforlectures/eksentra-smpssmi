<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassEnrollment;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Mengatur penempatan 6 kelas (7A, 7B, 8A, 8B, 9A, 9B) dengan masing-masing 10 siswa.
     * Total: 60 enrollments pada TA 2025/2026 dan 60 enrollments pada TA 2026/2027.
     */
    public function run(): void
    {
        $year2025 = AcademicYear::where('year_name', '2025/2026')->first();
        $year2026 = AcademicYear::where('year_name', '2026/2027')->first();

        $classes = ClassRoom::all()->keyBy('name');

        if (!$year2025 || !$year2026 || $classes->isEmpty()) {
            return;
        }

        // ========================================================
        // 1. TAHUN AJARAN 2025/2026 (6 Kelas x 10 Siswa = 60 Siswa)
        // ========================================================
        
        // 9A (2025/2026) -> 10 siswa lulus (Alumni)
        for ($i = 1; $i <= 10; $i++) {
            $nis = sprintf('232409%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['9A'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2025->id],
                    ['class_room_id' => $classes['9A']->id, 'status' => 'lulus']
                );
            }
        }

        // 9B (2025/2026) -> 10 siswa lulus (Alumni)
        for ($i = 11; $i <= 20; $i++) {
            $nis = sprintf('232409%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['9B'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2025->id],
                    ['class_room_id' => $classes['9B']->id, 'status' => 'lulus']
                );
            }
        }

        // 8A (2025/2026) -> 10 siswa naik ke 9A
        for ($i = 1; $i <= 10; $i++) {
            $nis = sprintf('242508%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['8A'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2025->id],
                    ['class_room_id' => $classes['8A']->id, 'status' => 'aktif']
                );
            }
        }

        // 8B (2025/2026) -> 10 siswa naik ke 9B
        for ($i = 11; $i <= 20; $i++) {
            $nis = sprintf('242508%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['8B'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2025->id],
                    ['class_room_id' => $classes['8B']->id, 'status' => 'aktif']
                );
            }
        }

        // 7A (2025/2026) -> 10 siswa naik ke 8A
        for ($i = 1; $i <= 10; $i++) {
            $nis = sprintf('252607%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['7A'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2025->id],
                    ['class_room_id' => $classes['7A']->id, 'status' => 'aktif']
                );
            }
        }

        // 7B (2025/2026) -> 10 siswa naik ke 8B
        for ($i = 11; $i <= 20; $i++) {
            $nis = sprintf('252607%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['7B'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2025->id],
                    ['class_room_id' => $classes['7B']->id, 'status' => 'aktif']
                );
            }
        }

        // ========================================================
        // 2. TAHUN AJARAN 2026/2027 (6 Kelas x 10 Siswa = 60 Siswa)
        // ========================================================

        // 9A (2026/2027) -> Naik dari 8A tahun lalu
        for ($i = 1; $i <= 10; $i++) {
            $nis = sprintf('242508%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['9A'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2026->id],
                    ['class_room_id' => $classes['9A']->id, 'status' => 'aktif']
                );
            }
        }

        // 9B (2026/2027) -> Naik dari 8B tahun lalu
        for ($i = 11; $i <= 20; $i++) {
            $nis = sprintf('242508%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['9B'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2026->id],
                    ['class_room_id' => $classes['9B']->id, 'status' => 'aktif']
                );
            }
        }

        // 8A (2026/2027) -> Naik dari 7A tahun lalu
        for ($i = 1; $i <= 10; $i++) {
            $nis = sprintf('252607%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['8A'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2026->id],
                    ['class_room_id' => $classes['8A']->id, 'status' => 'aktif']
                );
            }
        }

        // 8B (2026/2027) -> Naik dari 7B tahun lalu
        for ($i = 11; $i <= 20; $i++) {
            $nis = sprintf('252607%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['8B'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2026->id],
                    ['class_room_id' => $classes['8B']->id, 'status' => 'aktif']
                );
            }
        }

        // 7A (2026/2027) -> Siswa baru masuk angkatan 2026
        for ($i = 1; $i <= 10; $i++) {
            $nis = sprintf('262707%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['7A'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2026->id],
                    ['class_room_id' => $classes['7A']->id, 'status' => 'aktif']
                );
            }
        }

        // 7B (2026/2027) -> Siswa baru masuk angkatan 2026
        for ($i = 11; $i <= 20; $i++) {
            $nis = sprintf('262707%03d', $i);
            $user = User::where('username', $nis)->first();
            if ($user && isset($classes['7B'])) {
                ClassEnrollment::updateOrCreate(
                    ['student_id' => $user->id, 'academic_year_id' => $year2026->id],
                    ['class_room_id' => $classes['7B']->id, 'status' => 'aktif']
                );
            }
        }
    }
}
