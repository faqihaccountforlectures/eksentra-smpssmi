<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StudentStatusUpdateTest extends TestCase
{
    use DatabaseTransactions;

    private function createTestStudent(): User
    {
        $nis = '77' . rand(100000, 999999);
        $user = User::create([
            'username' => $nis,
            'password' => 'secret123',
            'role' => 'siswa',
            'must_change_password' => false,
        ]);

        StudentProfile::create([
            'user_id' => $user->id,
            'full_name' => 'Siswa Test Status',
            'nis' => $nis,
            'gender' => 'laki-laki',
            'birth_date' => '2012-05-15',
            'phone_number' => '081234567890',
            'parent_phone' => '081298765432',
            'status' => 'aktif',
        ]);

        return $user;
    }

    public function test_admin_can_update_student_status_to_keluar(): void
    {
        $admin = User::where('role', 'admin')->first();
        $student = $this->createTestStudent();

        $response = $this->actingAs($admin)->put(route('students.update', $student->id), [
            'full_name' => $student->studentProfile->full_name,
            'nis' => $student->studentProfile->nis,
            'gender' => $student->studentProfile->gender,
            'birth_date' => $student->studentProfile->birth_date->format('Y-m-d'),
            'phone_number' => '081234567890',
            'parent_phone' => '081298765432',
            'status' => 'keluar',
        ]);

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success');

        $student->refresh();
        $this->assertEquals('keluar', $student->studentProfile->status);

        // Pastikan activity log tercatat
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'STUDENT_STATUS_CHANGED',
        ]);
    }

    public function test_student_with_keluar_status_cannot_login(): void
    {
        $student = $this->createTestStudent();
        $student->studentProfile->update(['status' => 'keluar']);

        $response = $this->post('/login', [
            'login_type' => 'siswa',
            'nis' => $student->studentProfile->nis,
            'password' => 'secret123',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('dinonaktifkan (keluar)', session('error'));
        $this->assertGuest();
    }
}
