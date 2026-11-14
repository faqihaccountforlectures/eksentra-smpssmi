<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat atau perbarui Akun Admin Utama Eksentra
        User::updateOrCreate(
            ['role' => 'admin'],
            [
                'username' => 'admin_eksentra',
                'password' => Hash::make('smpsmi_admin'),
            ]
        );
    }
}
