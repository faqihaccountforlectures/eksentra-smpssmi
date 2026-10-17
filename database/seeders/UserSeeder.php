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
        // Buat Akun Admin Default langsung di seeder
        User::create([
            'username' => 'admin',
            'password' => Hash::make('smpsmi_admin'),
            'role' => 'admin',
        ]);
    }
}
