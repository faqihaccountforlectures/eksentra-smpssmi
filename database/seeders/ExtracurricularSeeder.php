<?php

namespace Database\Seeders;

use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExtracurricularSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 7 ekstrakurikuler sesuai formulir pendaftaran SMPS Science Mutiara Insani.
     */
    public function run(): void
    {
        $extracurriculars = [
            [
                'name' => 'PMR',
                'coach_username' => 'pembina.pmr',
                'schedule' => 'Setiap Sabtu, 08.00 - 10.00 WIB',
                'description' => 'Kegiatan kepalangmerahan remaja untuk melatih keterampilan pertolongan pertama, kesiapsiagaan bencana, dan kepedulian sosial.',
                'fee' => 100000,
            ],
            [
                'name' => 'Paskibra',
                'coach_username' => 'pembina.paskibra',
                'schedule' => 'Setiap Jumat, 14.30 - 16.30 WIB',
                'description' => 'Pelatihan baris-berbaris (PBB), formasi pengibaran bendera, kedisiplinan mental, dan pembentukan jiwa kepemimpinan.',
                'fee' => 100000,
            ],
            [
                'name' => 'Basket',
                'coach_username' => 'pembina.basket',
                'schedule' => 'Setiap Selasa & Kamis, 15.30 - 17.00 WIB',
                'description' => 'Latihan teknik dasar bola basket, taktik penyerangan dan pertahanan tim, serta persiapan kompetisi antarpelajar.',
                'fee' => 100000,
            ],
            [
                'name' => 'Futsal',
                'coach_username' => 'pembina.futsal',
                'schedule' => 'Setiap Rabu, 15.30 - 17.30 WIB',
                'description' => 'Pengembangan bakat sepak bola mini/futsal, penguasaan bola, kerjasama tim, dan partisipasi turnamen.',
                'fee' => 100000,
            ],
            [
                'name' => 'Archery (Panahan)',
                'coach_username' => 'pembina.archery',
                'schedule' => 'Setiap Sabtu, 10.00 - 12.00 WIB',
                'description' => 'Olahraga panahan sunnah untuk melatih konsentrasi tinggi, kestabilan emosi, ketepatan sasaran, dan ketangkasan.',
                'fee' => 100000,
            ],
            [
                'name' => 'Musik',
                'coach_username' => 'pembina.musik',
                'schedule' => 'Setiap Senin, 15.00 - 16.30 WIB',
                'description' => 'Eksplorasi bakat seni musik modern dan tradisional, olah vokal, gitar, keyboard, dan persiapan pementasan seni sekolah.',
                'fee' => 100000,
            ],
            [
                'name' => 'Science Club',
                'coach_username' => 'pembina.science',
                'schedule' => 'Setiap Kamis, 14.30 - 16.00 WIB',
                'description' => 'Eksperimen sains aplikatif, penelitian ilmiah remaja (KIR), dan bimbingan persiapan olimpiade sains nasional (OSN).',
                'fee' => 100000,
            ],
        ];

        foreach ($extracurriculars as $data) {
            $coach = User::where('username', $data['coach_username'])->first();

            if ($coach) {
                Extracurricular::updateOrCreate(
                    ['name' => $data['name']],
                    [
                        'coach_id' => $coach->id,
                        'schedule' => $data['schedule'],
                        'description' => $data['description'],
                        'fee' => $data['fee'],
                    ]
                );
            }
        }
    }
}
