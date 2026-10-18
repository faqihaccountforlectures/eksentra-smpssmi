<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat data 80 siswa dengan profil lengkap dan nama realistis untuk 6 kelas (10 siswa/kelas).
     */
    public function run(): void
    {
        $students = [
            // ========================================================
            // COHORT 1A: Kelas 9A pada 2025/2026 -> Alumni (Lulus) di 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '232409001', 'name' => 'Aditia Pratama', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409002', 'name' => 'Annisa Putri Azzahra', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409003', 'name' => 'Bagas Saputra', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409004', 'name' => 'Cantika Dewi', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409005', 'name' => 'Daffa Rizky Ramadhan', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409006', 'name' => 'Dinda Ayu Lestari', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409007', 'name' => 'Fajar Hidayat', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409008', 'name' => 'Gita Maharani', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409009', 'name' => 'Ilham Maulana', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409010', 'name' => 'Jessica Febriani', 'gender' => 'perempuan', 'status' => 'lulus'],

            // ========================================================
            // COHORT 1B: Kelas 9B pada 2025/2026 -> Alumni (Lulus) di 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '232409011', 'name' => 'Kevin Sanjaya', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409012', 'name' => 'Laila Nur Safitri', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409013', 'name' => 'Muhammad Fikri', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409014', 'name' => 'Nabila Syifa', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409015', 'name' => 'Pandu Wicaksono', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409016', 'name' => 'Qonita Amalia', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409017', 'name' => 'Raditya Nugraha', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409018', 'name' => 'Rania Zahra', 'gender' => 'perempuan', 'status' => 'lulus'],
            ['nis' => '232409019', 'name' => 'Satria Putra', 'gender' => 'laki-laki', 'status' => 'lulus'],
            ['nis' => '232409020', 'name' => 'Tiara Andini', 'gender' => 'perempuan', 'status' => 'lulus'],

            // ========================================================
            // COHORT 2A: Kelas 8A pada 2025/2026 -> Kelas 9A pada 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '242508001', 'name' => 'Arya Bima Sakti', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508002', 'name' => 'Aulia Rahmawati', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508003', 'name' => 'Bayu Aji Pamungkas', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508004', 'name' => 'Citra Kirana', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508005', 'name' => 'Denis Setiawan', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508006', 'name' => 'Dewi Sartika', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508007', 'name' => 'Farhan Alfarizi', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508008', 'name' => 'Hana Salsabila', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508009', 'name' => 'Irfan Hakim', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508010', 'name' => 'Kirana Putri', 'gender' => 'perempuan', 'status' => 'aktif'],

            // ========================================================
            // COHORT 2B: Kelas 8B pada 2025/2026 -> Kelas 9B pada 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '242508011', 'name' => 'Aldo Firmansyah', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508012', 'name' => 'Amanda Manopo', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508013', 'name' => 'Bintang Pratama', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508014', 'name' => 'Chelsea Olivia', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508015', 'name' => 'Doni Salmanan', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508016', 'name' => 'Elsa Anindita', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508017', 'name' => 'Fikram Alamsyah', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508018', 'name' => 'Gladys Veronica', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '242508019', 'name' => 'Haris Fadillah', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '242508020', 'name' => 'Indah Permatasari', 'gender' => 'perempuan', 'status' => 'aktif'],

            // ========================================================
            // COHORT 3A: Kelas 7A pada 2025/2026 -> Kelas 8A pada 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '252607001', 'name' => 'Alif Ramadhan', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607002', 'name' => 'Bella Anggraini', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607003', 'name' => 'Candra Wijaya', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607004', 'name' => 'Dian Permata', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607005', 'name' => 'Eko Prasetyo', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607006', 'name' => 'Fitri Handayani', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607007', 'name' => 'Galang Pratama', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607008', 'name' => 'Hani Nurul', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607009', 'name' => 'Iqbal Kurniawan', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607010', 'name' => 'Jihan Fahira', 'gender' => 'perempuan', 'status' => 'aktif'],

            // ========================================================
            // COHORT 3B: Kelas 7B pada 2025/2026 -> Kelas 8B pada 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '252607011', 'name' => 'Kuncoro Jati', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607012', 'name' => 'Lestari Wulandari', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607013', 'name' => 'Maulana Malik', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607014', 'name' => 'Nadya Zaskia', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607015', 'name' => 'Oki Setiana', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607016', 'name' => 'Putri Rahayu', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607017', 'name' => 'Rafi Ahmad', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607018', 'name' => 'Salma Hayek', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '252607019', 'name' => 'Taufik Hidayat', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '252607020', 'name' => 'Ulfa Safira', 'gender' => 'perempuan', 'status' => 'aktif'],

            // ========================================================
            // COHORT 4A: Siswa Baru Masuk Kelas 7A pada 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '262707001', 'name' => 'Abyan Farabi', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707002', 'name' => 'Aqila Azkadina', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707003', 'name' => 'Bilal Al-Ghifari', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707004', 'name' => 'Calista Azalea', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707005', 'name' => 'Dzaky Faeyza', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707006', 'name' => 'Elvira Mahadewi', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707007', 'name' => 'Fadhil Rahman', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707008', 'name' => 'Ghina Kamila', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707009', 'name' => 'Hafizh Syahputra', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707010', 'name' => 'Inayah Wulandari', 'gender' => 'perempuan', 'status' => 'aktif'],

            // ========================================================
            // COHORT 4B: Siswa Baru Masuk Kelas 7B pada 2026/2027 (10 Siswa)
            // ========================================================
            ['nis' => '262707011', 'name' => 'Jibran Al-Farisi', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707012', 'name' => 'Kayla Azzahra', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707013', 'name' => 'Luqman Hakim', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707014', 'name' => 'Marwah Shafira', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707015', 'name' => 'Naufal Arkan', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707016', 'name' => 'Olivia Paramitha', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707017', 'name' => 'Pasha Pratama', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707018', 'name' => 'Qiana Nadhira', 'gender' => 'perempuan', 'status' => 'aktif'],
            ['nis' => '262707019', 'name' => 'Rayhan Fakhri', 'gender' => 'laki-laki', 'status' => 'aktif'],
            ['nis' => '262707020', 'name' => 'Syifa Fauziah', 'gender' => 'perempuan', 'status' => 'aktif'],
        ];

        $studentPassword = Hash::make('siswasmpsmi');

        foreach ($students as $index => $item) {
            $user = User::updateOrCreate(
                ['username' => $item['nis']],
                [
                    'password' => $studentPassword,
                    'role' => 'siswa',
                    'must_change_password' => false,
                ]
            );

            // Nomor HP realistis untuk siswa dan orang tua
            $phoneStudent = '08' . sprintf('%010d', 1210000000 + $index + 1);
            $phoneParent  = '08' . sprintf('%010d', 1310000000 + $index + 1);

            StudentProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $item['nis'],
                    'full_name' => $item['name'],
                    'gender' => $item['gender'],
                    'phone_number' => $phoneStudent,
                    'parent_phone' => $phoneParent,
                    'status' => $item['status'],
                ]
            );
        }
    }
}
