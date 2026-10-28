<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MediumPrioritySecurityTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test Poin A: Rate Limiter mengunci akun yang salah berulang kali,
     * tetapi tidak mengunci akun lain yang berbeda.
     */
    public function test_login_rate_limiter_isolated_per_account(): void
    {
        // 5 kali percobaan gagal untuk akun siswa
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'login_type' => 'siswa',
                'nis'        => '242508011',
                'password'   => 'passwordsalah',
            ]);
        }

        // Percobaan ke-6 untuk akun yang sama harus terkena limit
        $response = $this->post('/login', [
            'login_type' => 'siswa',
            'nis'        => '242508011',
            'password'   => 'passwordsalah',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Terlalu banyak percobaan', session('error'));

        // Akun lain (misal pembina) TIDAK boleh terkunci meskipun dari IP yang sama
        $coachResponse = $this->post('/login', [
            'login_type' => 'coach',
            'username'   => 'pembina.musik',
            'password'   => 'passwordsalah',
        ]);

        // Error-nya adalah username/password tidak cocok, BUKAN terkunci rate limit
        $coachResponse->assertSessionHas('error');
        $this->assertStringNotContainsString('Terlalu banyak percobaan', session('error'));
    }

    /**
     * Test Poin B: HTTP Security Headers disertakan pada setiap respon web.
     */
    public function test_http_security_headers_present(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
    }

    /**
     * Test Poin C: Activity Log dicatat ke database dan Admin dapat melihat halaman audit trail.
     */
    public function test_activity_logging_and_admin_view(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Catat aktivitas uji coba
        ActivityLog::record(
            'TEST_ACTION',
            'Pengujian pencatatan audit trail keamanan',
            $admin
        );

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action'  => 'TEST_ACTION',
        ]);

        // Admin dapat mengakses halaman log aktivitas
        $response = $this->actingAs($admin)->get('/admin/activity-logs');
        $response->assertStatus(200);
        $response->assertSee('Riwayat Audit Trail');
        $response->assertSee('TEST_ACTION');
    }

    /**
     * Test Poin C2: Verifikasi pembayaran mencatat log aktivitas dengan benar.
     */
    public function test_payment_verify_records_activity_log(): void
    {
        $admin = User::where('role', 'admin')->first();
        $payment = \App\Models\Payment::first();

        if ($payment) {
            $response = $this->actingAs($admin)->patch("/admin/payments/{$payment->id}/verify");
            $response->assertSessionHas('success');

            $this->assertDatabaseHas('activity_logs', [
                'user_id' => $admin->id,
                'action'  => 'PAYMENT_VERIFIED',
            ]);

            // Test tolak pembayaran
            $rejectResponse = $this->actingAs($admin)->patch("/admin/payments/{$payment->id}/reject");
            $rejectResponse->assertSessionHas('success');

            $this->assertDatabaseHas('activity_logs', [
                'user_id' => $admin->id,
                'action'  => 'PAYMENT_REJECTED',
            ]);
        }
    }

    /**
     * Test Poin C3: Penilaian ekskul oleh pembina mencatat log aktivitas lengkap (nama siswa, NIS, nilai).
     */
    public function test_coach_update_grade_records_activity_log(): void
    {
        $coach = User::where('role', 'coach')->first();
        $coach->update(['must_change_password' => false]);

        $extracurricular = \App\Models\Extracurricular::where('coach_id', $coach->id)->first();
        
        if ($extracurricular) {
            $registration = $extracurricular->registrations()->where('status', 'approved')->first();
            $academicYear = \App\Models\AcademicYear::first();

            if ($registration && $academicYear) {
                $response = $this->actingAs($coach)->post("/coach/extracurriculars/{$extracurricular->id}/grade/{$registration->id}", [
                    'academic_year_id' => $academicYear->id,
                    'ganjil_grade'     => 'A',
                    'ganjil_score'     => 92,
                    'ganjil_notes'     => 'Sangat baik',
                ]);
                $response->assertSessionHas('success');

                $student = $registration->student;
                $studentName = $student->studentProfile->full_name ?? $student->display_name;

                $this->assertDatabaseHas('activity_logs', [
                    'user_id' => $coach->id,
                    'action'  => 'GRADE_UPDATED',
                ]);

                $log = ActivityLog::where('action', 'GRADE_UPDATED')->latest()->first();
                $this->assertStringContainsString($studentName, $log->description);
                $this->assertStringContainsString('Ganjil: A', $log->description);
                $this->assertStringContainsString('92', $log->description);
            }
        }
    }

    /**
     * Test Poin D: Validasi password baru mewajibkan kombinasi huruf dan angka.
     */
    public function test_password_complexity_validation(): void
    {
        $admin = User::where('role', 'admin')->first();
        $admin->update(['password' => Hash::make('passwordLama123')]);

        // 1. Password hanya huruf (tanpa angka) -> Harus gagal
        $response1 = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password'      => 'passwordLama123',
            'password'              => 'hanyahurufsaja',
            'password_confirmation' => 'hanyahurufsaja',
        ]);
        $response1->assertSessionHasErrors(['password']);

        // 2. Password hanya angka (tanpa huruf) -> Harus gagal
        $response2 = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password'      => 'passwordLama123',
            'password'              => '1234567890',
            'password_confirmation' => '1234567890',
        ]);
        $response2->assertSessionHasErrors(['password']);

        // 3. Password valid (minimal 8 karakter kombinasi huruf & angka) -> Berhasil
        $response3 = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password'      => 'passwordLama123',
            'password'              => 'adminBaru2026',
            'password_confirmation' => 'adminBaru2026',
        ]);
        $response3->assertSessionHas('success');

        // Pastikan juga tercatat di ActivityLog
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action'  => 'ADMIN_PASSWORD_CHANGED',
        ]);
    }
}
