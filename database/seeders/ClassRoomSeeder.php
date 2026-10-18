<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Membuat 6 ruang kelas: 7A, 7B, 8A, 8B, 9A, dan 9B.
     */
    public function run(): void
    {
        $classRooms = [
            ['name' => '7A', 'grade_level' => 7],
            ['name' => '7B', 'grade_level' => 7],
            ['name' => '8A', 'grade_level' => 8],
            ['name' => '8B', 'grade_level' => 8],
            ['name' => '9A', 'grade_level' => 9],
            ['name' => '9B', 'grade_level' => 9],
        ];

        foreach ($classRooms as $class) {
            ClassRoom::updateOrCreate(
                ['name' => $class['name']],
                ['grade_level' => $class['grade_level']]
            );
        }
    }
}
