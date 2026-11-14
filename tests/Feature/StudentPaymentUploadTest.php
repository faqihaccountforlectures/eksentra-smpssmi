<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentPaymentUploadTest extends TestCase
{
    use DatabaseTransactions;

    public function test_payment_proof_rejects_file_larger_than_10mb(): void
    {
        Storage::fake('local');

        $student = User::where('role', 'siswa')->first();
        if (!$student) {
            $student = User::factory()->create(['role' => 'siswa']);
        }
        $student->must_change_password = false;
        $student->save();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $extracurricular = Extracurricular::first();

        $registration = ExtracurricularRegistration::create([
            'student_id' => $student->id,
            'extracurricular_id' => $extracurricular->id,
            'academic_year_id' => $activeYear->id,
            'status' => 'approved',
            'created_at' => now()->startOfYear(),
        ]);

        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentMonth = $monthsName[(int) date('n')];

        // File berukuran 11MB (11264 KB) melebihi batas 10MB (10240 KB)
        $oversizedFile = UploadedFile::fake()->create('bukti_besar.jpg', 11264, 'image/jpeg');

        $response = $this->actingAs($student)->post(route('siswa.payments.store'), [
            'registration_id' => $registration->id,
            'month' => $currentMonth,
            'year' => (int) date('Y'),
            'payment_method' => 'transfer',
            'proof_of_payment' => $oversizedFile,
        ]);

        $response->assertSessionHasErrors(['proof_of_payment']);
        $errors = session('errors')->get('proof_of_payment');
        $this->assertContains('Ukuran file bukti pembayaran maksimal 10MB.', $errors);
    }

    public function test_payment_proof_accepts_file_up_to_10mb(): void
    {
        Storage::fake('local');

        $student = User::where('role', 'siswa')->first();
        if (!$student) {
            $student = User::factory()->create(['role' => 'siswa']);
        }
        $student->must_change_password = false;
        $student->save();

        $activeYear = AcademicYear::where('is_active', true)->first();
        $extracurricular = Extracurricular::first();

        $registration = ExtracurricularRegistration::create([
            'student_id' => $student->id,
            'extracurricular_id' => $extracurricular->id,
            'academic_year_id' => $activeYear->id,
            'status' => 'approved',
            'created_at' => now()->startOfYear(),
        ]);

        $monthsName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentMonth = $monthsName[(int) date('n')];

        // File berukuran 5MB (5120 KB) di bawah batas 10MB
        $validFile = UploadedFile::fake()->create('bukti_5mb.jpg', 5120, 'image/jpeg');

        $response = $this->actingAs($student)->post(route('siswa.payments.store'), [
            'registration_id' => $registration->id,
            'month' => $currentMonth,
            'year' => (int) date('Y'),
            'payment_method' => 'transfer',
            'proof_of_payment' => $validFile,
        ]);

        $response->assertSessionDoesntHaveErrors(['proof_of_payment']);
        $response->assertSessionHas('success');
    }
}
