<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Extracurricular;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AlumniAccessTest extends TestCase
{
    use DatabaseTransactions;

    private function createAlumniUser(): User
    {
        $nis = '99' . rand(100000, 999999);
        $user = User::create([
            'username' => $nis,
            'password' => 'secret123',
            'role' => 'siswa',
            'must_change_password' => false,
        ]);

        StudentProfile::create([
            'user_id' => $user->id,
            'full_name' => 'Siswa Test Alumni',
            'nis' => $nis,
            'gender' => 'laki-laki',
            'birth_date' => '2010-08-17',
            'status' => 'lulus',
        ]);

        return $user;
    }

    public function test_alumni_cannot_register_extracurricular(): void
    {
        $alumni = $this->createAlumniUser();
        $extracurricular = Extracurricular::first();

        $response = $this->actingAs($alumni)->post(route('extracurricular-registrations.store'), [
            'extracurricular_id' => $extracurricular->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('peserta didik yang berstatus aktif', session('error'));
    }

    public function test_alumni_has_no_unpaid_bills_in_new_academic_year(): void
    {
        $alumni = $this->createAlumniUser();

        $response = $this->actingAs($alumni)->get(route('siswa.payments.index'));
        $response->assertStatus(200);
        $response->assertSee('Status Anda: Alumni (Lulus)');
        $response->assertSee('Tidak ada kewajiban iuran ekstrakurikuler');
    }
}
