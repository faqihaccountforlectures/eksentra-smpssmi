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
            ],
            [
                'username' => 'pembina.paskibra',
                'full_name' => 'Ahmad Fauzi, S.Pd',
                'gender' => 'laki-laki',
                'phone_number' => '081234567802',
            ],
            [
                'username' => 'pembina.basket',
                'full_name' => 'Rian Pratama, S.Pd',
                'gender' => 'laki-laki',
                'phone_number' => '081234567803',
            ],
            [
                'username' => 'pembina.futsal',
                'full_name' => 'Dimas Setiawan, S.Pd',
                'gender' => 'laki-laki',
                'phone_number' => '081234567804',
            ],
            [
                'username' => 'pembina.archery',
                'full_name' => 'Indra Wijaya, S.T',
                'gender' => 'laki-laki',
                'phone_number' => '081234567805',
            ],
            [
                'username' => 'pembina.musik',
                'full_name' => 'Ferry Ferdian, S.Sn',
                'gender' => 'laki-laki',
                'phone_number' => '081234567806',
            ],
            [
                'username' => 'pembina.science',
                'full_name' => 'Dr. Nurul Hidayah, M.Si',
                'gender' => 'perempuan',
                'phone_number' => '081234567807',
            ],
        ];

        foreach ($coaches as $data) {
            $user = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'password' => Hash::make('pembinasmpsmi'),
                    'role' => 'coach',
                    'must_change_password' => false,
                ]
            );

            CoachProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $data['full_name'],
                    'gender' => $data['gender'],
                    'phone_number' => $data['phone_number'],
                ]
            );
        }
    }
}
