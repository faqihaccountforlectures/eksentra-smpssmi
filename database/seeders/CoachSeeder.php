<?php

namespace Database\Seeders;

use App\Models\CoachProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 7 akun pembina (1 pembina untuk 1 ekstrakurikuler).
     */
    public function run(): void
    {
        $coaches = [
            [
                'username' => 'pembina.pmr',
                'full_name' => 'Ns. Siti Rahmawati, S.Kep',
                'gender' => 'perempuan',
                'phone_number' => '081234567801',
                'birth_date' => '1996-02-24', // Usia 30 tahun
            ],
            [
                'username' => 'pembina.paskibra',
                'full_name' => 'Ahmad Fauzi, S.Pd',
                'gender' => 'laki-laki',
                'phone_number' => '081234567802',
                'birth_date' => '1992-02-05', // Usia 34 tahun
            ],
            [
                'username' => 'pembina.basket',
                'full_name' => 'Rian Pratama, S.Pd',
                'gender' => 'laki-laki',
                'phone_number' => '081234567803',
                'birth_date' => '1978-01-10', // Usia 48 tahun
            ],
            [
                'username' => 'pembina.futsal',
                'full_name' => 'Dimas Setiawan, S.Pd',
                'gender' => 'laki-laki',
                'phone_number' => '081234567804',
                'birth_date' => '1984-05-01', // Usia 42 tahun
            ],
            [
                'username' => 'pembina.archery',
                'full_name' => 'Indra Wijaya, S.T',
                'gender' => 'laki-laki',
                'phone_number' => '081234567805',
                'birth_date' => '1982-11-19', // Usia 44 tahun
            ],
            [
                'username' => 'pembina.musik',
                'full_name' => 'Ferry Ferdian, S.Sn',
                'gender' => 'laki-laki',
                'phone_number' => '081234567806',
                'birth_date' => '1989-02-13', // Usia 37 tahun
            ],
            [
                'username' => 'pembina.science',
                'full_name' => 'Dr. Nurul Hidayah, M.Si',
                'gender' => 'perempuan',
                'phone_number' => '081234567807',
                'birth_date' => '1994-04-30', // Usia 32 tahun
            ],
        ];

        foreach ($coaches as $data) {
            $birthDateStr = $data['birth_date'];
            $birthDateFormatted = str_replace('-', '', $birthDateStr); // YYYYMMDD

            $user = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'password' => Hash::make($birthDateFormatted),
                    'role' => 'coach',
                    'must_change_password' => true,
                ]
            );

            CoachProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name'    => $data['full_name'],
                    'gender'       => $data['gender'],
                    'birth_date'   => $birthDateStr,
                    'phone_number' => $data['phone_number'],
                ]
            );
        }
    }
}
