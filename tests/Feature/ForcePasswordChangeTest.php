<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\CoachProfile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForcePasswordChangeTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseTransactions;
    public function test_student_login_with_birth_date_redirects_to_force_change_password(): void
    {
        $student = User::where('role', 'siswa')->with('studentProfile')->first();
        $birthDatePass = $student->studentProfile->birth_date->format('Ymd');

        $response = $this->post('/login', [
            'login_type' => 'siswa',
            'nis' => $student->username,
            'password' => $birthDatePass,
        ]);

        $response->assertRedirect(route('password.force_change'));
        $this->assertAuthenticatedAs($student);
    }

    public function test_student_with_must_change_password_cannot_access_dashboard(): void
    {
        $student = User::where('role', 'siswa')->first();
        $student->update(['must_change_password' => true]);

        $response = $this->actingAs($student)->get('/dashboard');

        $response->assertRedirect(route('password.force_change'));
    }

    public function test_coach_login_with_birth_date_redirects_to_force_change_password(): void
    {
        $coach = User::where('role', 'coach')->with('coachProfile')->first();
        $birthDatePass = $coach->coachProfile->birth_date->format('Ymd');

        $response = $this->post('/login', [
            'login_type' => 'coach',
            'username' => $coach->username,
            'password' => $birthDatePass,
        ]);

        $response->assertRedirect(route('password.force_change'));
        $this->assertAuthenticatedAs($coach);
    }

    public function test_force_change_password_rejects_birth_date_and_accepts_new_password(): void
    {
        $student = User::where('role', 'siswa')->with('studentProfile')->first();
        $birthDatePass = $student->studentProfile->birth_date->format('Ymd');
        $student->update(['must_change_password' => true]);

        // Coba ganti dengan password yang sama dengan tanggal lahir (harus gagal)
        $responseFail = $this->actingAs($student)->post(route('password.force_change.update'), [
            'password' => $birthDatePass,
            'password_confirmation' => $birthDatePass,
        ]);
        $responseFail->assertSessionHasErrors(['password']);

        // Ganti dengan password baru yang valid (minimal 8 karakter dengan huruf & angka)
        $newPass = 'RahasiaBaru123';
        $responseSuccess = $this->actingAs($student)->post(route('password.force_change.update'), [
            'password' => $newPass,
            'password_confirmation' => $newPass,
        ]);

        $responseSuccess->assertRedirect(route('dashboard'));
        $student->refresh();
        $this->assertFalse($student->must_change_password);
        $this->assertTrue(Hash::check($newPass, $student->password));
    }
}
