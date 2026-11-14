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

    public function test_alumni_cannot_register_extracurricular(): void
    {
        $alumni = User::where('role', 'siswa')
            ->whereHas('studentProfile', fn($q) => $q->where('status', 'lulus'))
            ->first();

        if (!$alumni) {
            $alumni = User::factory()->create(['role' => 'siswa']);
            StudentProfile::create([
                'user_id' => $alumni->id,
                'full_name' => 'Test Alumni',
                'nis' => '99999999',
                'gender' => 'laki-laki',
                'status' => 'lulus',
            ]);
        }
        $alumni->must_change_password = false;
        $alumni->save();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $extracurricular = Extracurricular::first();

        $response = $this->actingAs($alumni)->post(route('extracurricular-registrations.store'), [
            'extracurricular_id' => $extracurricular->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('peserta didik yang berstatus aktif', session('error'));
    }

    public function test_alumni_has_no_unpaid_bills_in_new_academic_year(): void
    {
        $alumni = User::where('role', 'siswa')
            ->whereHas('studentProfile', fn($q) => $q->where('status', 'lulus'))
            ->first();

        if (!$alumni) {
            $alumni = User::factory()->create(['role' => 'siswa']);
            StudentProfile::create([
                'user_id' => $alumni->id,
                'full_name' => 'Test Alumni',
                'nis' => '99999998',
                'gender' => 'laki-laki',
                'status' => 'lulus',
            ]);
        }
        $alumni->must_change_password = false;
        $alumni->save();

        $response = $this->actingAs($alumni)->get(route('siswa.payments.index'));
        $response->assertStatus(200);
        $response->assertSee('Status Anda: Alumni (Lulus)');
        $response->assertSee('Tidak ada kewajiban iuran ekstrakurikuler');
    }
}
