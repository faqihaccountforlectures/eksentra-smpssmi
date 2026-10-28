<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ExtracurricularController;
use App\Http\Controllers\Admin\ExtracurricularRegistrationController;
use App\Http\Controllers\Siswa\StudentExtracurricularController;
use App\Http\Controllers\Siswa\StudentPaymentController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah rute web untuk aplikasi didaftarkan. Seluruh rute
| dimuat oleh RouteServiceProvider dan tergabung dalam grup
| middleware "web".
|
*/

// Rute Halaman Beranda (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Rute Halaman Autentikasi (Login)
Route::get('/login', function () {
    // Jika pengguna sudah login, arahkan langsung ke dasbor
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('login');

// Memproses formulir login manual (Email & Kata Sandi)
// throttle:login → maksimal 5 percobaan per menit berbasis kombinasi akun + IP (mencegah brute-force & false lockout)
Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:login')->name('login.post');

// Memproses proses keluar (Logout)
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Rute Wajib Ganti Password Default (Khusus Siswa & Pembina)
Route::middleware('auth')->group(function () {
    Route::get('/force-change-password', [ForcePasswordChangeController::class, 'show'])->name('password.force_change');
    Route::post('/force-change-password', [ForcePasswordChangeController::class, 'update'])->name('password.force_change.update');
});

// Rute Halaman Dasbor (Membutuhkan Autentikasi)
Route::get('/dashboard', function () {
    $role = Auth::user()->role;

    if ($role === 'admin') {
        return app(\App\Http\Controllers\Admin\DashboardController::class)->index();
    } elseif ($role === 'coach') {
        return app(\App\Http\Controllers\Coach\DashboardController::class)->index();
    } elseif ($role === 'siswa') {
        return app(\App\Http\Controllers\Siswa\DashboardController::class)->index();
    }

    Auth::logout();
    return redirect('/login')->with('error', 'Hak akses tidak valid.');
})->middleware('auth')->name('dashboard');

// ==========================================
// GRUP RUTE ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // ==========================================
    // RUTE MASTER DATA TAHUN AJARAN (ACADEMIC YEARS)
    // ==========================================
    Route::get('/admin/academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
    Route::post('/admin/academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
    Route::get('/admin/academic-years/{id}/edit', [AcademicYearController::class, 'edit'])->name('academic-years.edit');
    Route::put('/admin/academic-years/{id}', [AcademicYearController::class, 'update'])->name('academic-years.update');
    Route::delete('/admin/academic-years/{id}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');

    // ==========================================
    // RUTE MASTER DATA KELAS (CLASS ROOMS)
    // ==========================================
    Route::get('/admin/classes', [\App\Http\Controllers\Admin\ClassRoomController::class, 'index'])->name('classes.index');
    Route::post('/admin/classes', [\App\Http\Controllers\Admin\ClassRoomController::class, 'store'])->name('classes.store');
    Route::get('/admin/classes/{id}/edit', [\App\Http\Controllers\Admin\ClassRoomController::class, 'edit'])->name('classes.edit');
    Route::put('/admin/classes/{id}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'update'])->name('classes.update');
    Route::delete('/admin/classes/{id}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'destroy'])->name('classes.destroy');

    // ==========================================
    // RUTE EKSTRAKURIKULER (EXTRACURRICULARS)
    // ==========================================
    Route::resource('admin/extracurriculars', ExtracurricularController::class)->names([
        'index' => 'extracurriculars.index',
        'create' => 'extracurriculars.create',
        'store' => 'extracurriculars.store',
        'edit' => 'extracurriculars.edit',
        'update' => 'extracurriculars.update',
        'destroy' => 'extracurriculars.destroy',
    ]);

    // ==========================================
    // RUTE PERSETUJUAN PENDAFTARAN EKSKUL (ADMIN)
    // ==========================================
    Route::get('/admin/extracurricular-registrations', [ExtracurricularRegistrationController::class, 'index'])->name('extracurricular-registrations.index');
    Route::patch('/admin/extracurricular-registrations/{id}/approve', [ExtracurricularRegistrationController::class, 'approve'])->name('extracurricular-registrations.approve');
    Route::patch('/admin/extracurricular-registrations/{id}/reject', [ExtracurricularRegistrationController::class, 'reject'])->name('extracurricular-registrations.reject');

    // ==========================================
    // RUTE VERIFIKASI PEMBAYARAN EKSKUL (ADMIN)
    // ==========================================
    Route::get('/admin/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('/admin/payments/export/pdf', [AdminPaymentController::class, 'exportPdf'])->name('admin.payments.export.pdf');
    Route::patch('/admin/payments/{id}/verify', [AdminPaymentController::class, 'verify'])->name('admin.payments.verify');
    Route::patch('/admin/payments/{id}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');

    // ==========================================
    // RUTE PROFIL & PENGATURAN AKUN ADMIN
    // ==========================================
    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
    Route::put('/admin/profile', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.update-password');

    // ==========================================
    // RUTE MASTER DATA COACH / PEMBINA EKSTRAKURIKULER
    // ==========================================
    Route::get('/admin/coaches', [\App\Http\Controllers\Admin\CoachController::class, 'index'])->name('coaches.index');
    Route::get('/admin/coaches/template', [\App\Http\Controllers\Admin\CoachController::class, 'downloadTemplate'])->name('coaches.template');
    Route::post('/admin/coaches/import', [\App\Http\Controllers\Admin\CoachController::class, 'import'])->name('coaches.import');
    Route::get('/admin/coaches/create', [\App\Http\Controllers\Admin\CoachController::class, 'create'])->name('coaches.create');
    Route::post('/admin/coaches', [\App\Http\Controllers\Admin\CoachController::class, 'store'])->name('coaches.store');
    Route::get('/admin/coaches/{id}/edit', [\App\Http\Controllers\Admin\CoachController::class, 'edit'])->name('coaches.edit');
    Route::put('/admin/coaches/{id}', [\App\Http\Controllers\Admin\CoachController::class, 'update'])->name('coaches.update');
    Route::post('/admin/coaches/{id}/reset-password', [\App\Http\Controllers\Admin\CoachController::class, 'resetPassword'])->name('coaches.reset-password');
    Route::delete('/admin/coaches/{id}', [\App\Http\Controllers\Admin\CoachController::class, 'destroy'])->name('coaches.destroy');

    // ==========================================
    // RUTE DATA SISWA (STUDENTS)
    // ==========================================
    Route::get('/admin/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
    Route::get('/admin/students/template', [\App\Http\Controllers\Admin\StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::post('/admin/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'import'])->name('students.import');
    Route::get('/admin/students/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('students.create');
    Route::post('/admin/students', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('students.store');
    Route::get('/admin/students/{id}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('students.edit');
    Route::put('/admin/students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('students.update');
    Route::post('/admin/students/{id}/reset-password', [\App\Http\Controllers\Admin\StudentController::class, 'resetPassword'])->name('students.reset-password');
    Route::delete('/admin/students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');

    // ==========================================
    // RUTE PEMBAGIAN KELAS (CLASS ENROLLMENTS)
    // ==========================================
    Route::get('/admin/class-enrollments', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'index'])->name('class-enrollments.index');
    Route::get('/admin/class-enrollments/graduate/action', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'graduateStudents'])->name('class-enrollments.graduate');
    Route::post('/admin/class-enrollments/graduate/action', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'storeGraduation'])->name('class-enrollments.store-graduation');
    Route::get('/admin/class-enrollments/{class_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'show'])->name('class-enrollments.show');
    Route::get('/admin/class-enrollments/{class_id}/add-students', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'addStudents'])->name('class-enrollments.add-students');
    Route::post('/admin/class-enrollments/{class_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'storeStudents'])->name('class-enrollments.store-students');
    Route::get('/admin/class-enrollments/{class_id}/promote', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'promoteStudents'])->name('class-enrollments.promote');
    Route::post('/admin/class-enrollments/{class_id}/promote', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'storePromotion'])->name('class-enrollments.store-promotion');
    Route::delete('/admin/class-enrollments/{enrollment_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'destroy'])->name('class-enrollments.destroy');

    // ==========================================
    // RUTE AUDIT TRAIL / LOG AKTIVITAS (SECURITY)
    // ==========================================
    Route::get('/admin/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// ==========================================
// GRUP RUTE KHUSUS COACH (EKSTRAKURIKULER)
// ==========================================
Route::middleware(['auth', 'role:coach'])->prefix('coach')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Coach\DashboardController::class, 'index'])->name('coach.dashboard');
    Route::get('/profile', [\App\Http\Controllers\Coach\ProfileController::class, 'index'])->name('coach.profile.index');
    Route::put('/profile', [\App\Http\Controllers\Coach\ProfileController::class, 'update'])->name('coach.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Coach\ProfileController::class, 'updatePassword'])->name('coach.profile.update-password');
    Route::get('/extracurriculars', [\App\Http\Controllers\Coach\ExtracurricularController::class, 'index'])->name('coach.extracurriculars.index');
    Route::get('/extracurriculars/{id}', [\App\Http\Controllers\Coach\ExtracurricularController::class, 'show'])->name('coach.extracurriculars.show');
    Route::get('/extracurriculars/{id}/report/pdf', [\App\Http\Controllers\Coach\ExtracurricularController::class, 'exportPdf'])->name('coach.extracurriculars.report.pdf');
    Route::post('/extracurriculars/{id}/grade/{registration_id}', [\App\Http\Controllers\Coach\ExtracurricularController::class, 'updateGrade'])->name('coach.extracurriculars.grade');
});

// ==========================================
// GRUP RUTE KHUSUS SISWA
// ==========================================
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa/profile', [\App\Http\Controllers\Siswa\ProfileController::class, 'index'])->name('siswa.profile.index');
    Route::put('/siswa/profile', [\App\Http\Controllers\Siswa\ProfileController::class, 'update'])->name('siswa.profile.update');
    Route::put('/siswa/profile/password', [\App\Http\Controllers\Siswa\ProfileController::class, 'updatePassword'])->name('siswa.profile.update-password');
    Route::get('/siswa/extracurriculars', [StudentExtracurricularController::class, 'index'])->name('siswa.extracurriculars.index');
    Route::post('/siswa/extracurricular-registrations', [ExtracurricularRegistrationController::class, 'store'])->name('extracurricular-registrations.store');
    Route::delete('/siswa/extracurricular-registrations/{id}', [ExtracurricularRegistrationController::class, 'destroy'])->name('extracurricular-registrations.destroy');
    Route::get('/siswa/payments', [StudentPaymentController::class, 'index'])->name('siswa.payments.index');
    Route::post('/siswa/payments', [StudentPaymentController::class, 'store'])->name('siswa.payments.store');
});

// Rute Tampilan Bukti Transfer Pembayaran Aman (Dilindungi Otorisasi Hak Akses)
Route::middleware('auth')->get('/payments/{id}/proof', [\App\Http\Controllers\PaymentProofController::class, 'show'])->name('payments.proof');
