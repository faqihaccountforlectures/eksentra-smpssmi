<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AcademicYearSeeder::class,
            ClassRoomSeeder::class,
            UserSeeder::class,
            CoachSeeder::class,
            ExtracurricularSeeder::class,
            StudentSeeder::class,
            ClassEnrollmentSeeder::class,
            ExtracurricularRegistrationSeeder::class,
            PaymentSeeder::class,
            ExtracurricularGradeSeeder::class,
        ]);
    }
}
