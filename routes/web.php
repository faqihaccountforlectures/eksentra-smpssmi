<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\AuthController;
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
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

// Rute Autentikasi Google (SSO)
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// Memproses proses keluar (Logout)
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Rute Halaman Dasbor (Membutuhkan Autentikasi)
Route::get('/dashboard', function () {
    // Mengambil data hak akses (role) dari pengguna yang sedang masuk
    $role = Auth::user()->role;

    // Mengarahkan pengguna ke dasbor spesifik berdasarkan peran mereka
    if ($role === 'admin') {
        return app(\App\Http\Controllers\Admin\DashboardController::class)->index();
    } elseif ($role === 'guru' || $role === 'pembina') {
        return app(\App\Http\Controllers\Pembina\DashboardController::class)->index();
    } elseif ($role === 'siswa') {
        return app(\App\Http\Controllers\Siswa\DashboardController::class)->index();
    }

    // Jika peran tidak valid, keluarkan pengguna dan arahkan kembali ke halaman login
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
// Menampilkan halaman utama daftar tahun ajaran
Route::get('/admin/academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
// Menyimpan data tahun ajaran baru ke basis data
Route::post('/admin/academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
// Menampilkan formulir untuk mengubah data tahun ajaran
Route::get('/admin/academic-years/{id}/edit', [AcademicYearController::class, 'edit'])->name('academic-years.edit');
// Memperbarui data tahun ajaran pada basis data
Route::put('/admin/academic-years/{id}', [AcademicYearController::class, 'update'])->name('academic-years.update');
// Menghapus data tahun ajaran secara permanen
Route::delete('/admin/academic-years/{id}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');

// ==========================================
// RUTE MASTER DATA KELAS (CLASS ROOMS)
// ==========================================
// Menampilkan daftar kelas beserta wali kelas terkait
Route::get('/admin/classes', [\App\Http\Controllers\Admin\ClassRoomController::class, 'index'])->name('classes.index');
// Menyimpan data kelas baru ke basis data
Route::post('/admin/classes', [\App\Http\Controllers\Admin\ClassRoomController::class, 'store'])->name('classes.store');
// Menampilkan formulir untuk mengubah data kelas
Route::get('/admin/classes/{id}/edit', [\App\Http\Controllers\Admin\ClassRoomController::class, 'edit'])->name('classes.edit');
// Memperbarui data kelas pada basis data
Route::put('/admin/classes/{id}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'update'])->name('classes.update');
// Menghapus data kelas secara permanen
Route::delete('/admin/classes/{id}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'destroy'])->name('classes.destroy');


// ==========================================
// RUTE EKSTRAKURIKULER (EXTRACURRICULARS)
// ==========================================
// Menginisialisasi rute sumber daya (resource) untuk ekstrakurikuler
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
// Menampilkan daftar siswa yang mengajukan pendaftaran ekstrakurikuler
Route::get('/admin/extracurricular-registrations', [ExtracurricularRegistrationController::class, 'index'])->name('extracurricular-registrations.index');
// Memproses persetujuan atas pengajuan pendaftaran siswa
Route::patch('/admin/extracurricular-registrations/{id}/approve', [ExtracurricularRegistrationController::class, 'approve'])->name('extracurricular-registrations.approve');
// Memproses penolakan atas pengajuan pendaftaran siswa
Route::patch('/admin/extracurricular-registrations/{id}/reject', [ExtracurricularRegistrationController::class, 'reject'])->name('extracurricular-registrations.reject');

// ==========================================
// RUTE VERIFIKASI PEMBAYARAN EKSKUL (ADMIN)
// ==========================================
// Menampilkan riwayat pembayaran ekstrakurikuler dari seluruh siswa
Route::get('/admin/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
// Mengekspor laporan riwayat pembayaran ke PDF
Route::get('/admin/payments/export/pdf', [AdminPaymentController::class, 'exportPdf'])->name('admin.payments.export.pdf');
// Menyetujui keabsahan bukti pembayaran yang diunggah siswa
Route::patch('/admin/payments/{id}/verify', [AdminPaymentController::class, 'verify'])->name('admin.payments.verify');
// Menolak keabsahan bukti pembayaran yang diunggah siswa
Route::patch('/admin/payments/{id}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');

// ==========================================
// RUTE PROFIL & PENGATURAN AKUN ADMIN
// ==========================================
// Menampilkan halaman profil & pengaturan akun admin
Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
// Memperbarui data profil admin (Nama, Email, No HP)
Route::put('/admin/profile', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
// Memperbarui password akun admin & hentikan sesi di perangkat lain
Route::put('/admin/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.update-password');

// ==========================================
// RUTE MASTER DATA PEMBINA EKSTRAKURIKULER
// ==========================================
// Menampilkan daftar keseluruhan data pembina yang terdaftar
Route::get('/admin/pembina', [\App\Http\Controllers\Admin\PembinaController::class, 'index'])->name('pembina.index');
// Mengunduh berkas template CSV untuk impor data pembina
Route::get('/admin/pembina/template', [\App\Http\Controllers\Admin\PembinaController::class, 'downloadTemplate'])->name('pembina.template');
// Memproses impor massal data pembina dari berkas CSV/Excel
Route::post('/admin/pembina/import', [\App\Http\Controllers\Admin\PembinaController::class, 'import'])->name('pembina.import');
// Menampilkan formulir pendaftaran akun dan profil pembina baru
Route::get('/admin/pembina/create', [\App\Http\Controllers\Admin\PembinaController::class, 'create'])->name('pembina.create');
// Menyimpan data akun dan profil pembina ke basis data melalui transaksi DB
Route::post('/admin/pembina', [\App\Http\Controllers\Admin\PembinaController::class, 'store'])->name('pembina.store');
// Menampilkan formulir perubahan data profil pembina
Route::get('/admin/pembina/{id}/edit', [\App\Http\Controllers\Admin\PembinaController::class, 'edit'])->name('pembina.edit');
// Memperbarui data akun dan profil pembina pada basis data
Route::put('/admin/pembina/{id}', [\App\Http\Controllers\Admin\PembinaController::class, 'update'])->name('pembina.update');
// Menghapus akun dan profil pembina secara permanen
Route::delete('/admin/pembina/{id}', [\App\Http\Controllers\Admin\PembinaController::class, 'destroy'])->name('pembina.destroy');

// Alias kompatibilitas rute guru / teachers
Route::get('/admin/teachers', [\App\Http\Controllers\Admin\PembinaController::class, 'index'])->name('teachers.index');
Route::get('/admin/teachers/template', [\App\Http\Controllers\Admin\PembinaController::class, 'downloadTemplate'])->name('teachers.template');
Route::post('/admin/teachers/import', [\App\Http\Controllers\Admin\PembinaController::class, 'import'])->name('teachers.import');
Route::get('/admin/teachers/create', [\App\Http\Controllers\Admin\PembinaController::class, 'create'])->name('teachers.create');
Route::post('/admin/teachers', [\App\Http\Controllers\Admin\PembinaController::class, 'store'])->name('teachers.store');
Route::get('/admin/teachers/{id}/edit', [\App\Http\Controllers\Admin\PembinaController::class, 'edit'])->name('teachers.edit');
Route::put('/admin/teachers/{id}', [\App\Http\Controllers\Admin\PembinaController::class, 'update'])->name('teachers.update');
Route::delete('/admin/teachers/{id}', [\App\Http\Controllers\Admin\PembinaController::class, 'destroy'])->name('teachers.destroy');

// ==========================================
// RUTE DATA SISWA (STUDENTS)
// ==========================================
// Menampilkan daftar keseluruhan data siswa (termasuk informasi orang tua)
Route::get('/admin/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students.index');
// Mengunduh berkas template CSV untuk impor data siswa
Route::get('/admin/students/template', [\App\Http\Controllers\Admin\StudentController::class, 'downloadTemplate'])->name('students.template');
// Memproses impor massal data siswa dari berkas CSV/Excel
Route::post('/admin/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'import'])->name('students.import');
// Menampilkan formulir pendaftaran akun dan profil siswa baru
Route::get('/admin/students/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('students.create');
// Menyimpan data akun dan profil siswa ke basis data melalui transaksi DB
Route::post('/admin/students', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('students.store');
// Menampilkan formulir perubahan data profil siswa
Route::get('/admin/students/{id}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('students.edit');
// Memperbarui data akun dan profil siswa pada basis data
Route::put('/admin/students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('students.update');
// Menghapus akun dan profil siswa secara permanen
Route::delete('/admin/students/{id}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('students.destroy');

// ==========================================
// RUTE PEMBAGIAN KELAS (CLASS ENROLLMENTS)
// ==========================================
// Menampilkan daftar seluruh kelas pada tahun ajaran aktif beserta statistik jumlah siswa
Route::get('/admin/class-enrollments', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'index'])->name('class-enrollments.index');
// Menampilkan halaman khusus kelulusan dan penanganan tinggal kelas
Route::get('/admin/class-enrollments/graduate/action', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'graduateStudents'])->name('class-enrollments.graduate');
// Memproses kelulusan dan penempatan siswa yang tinggal kelas
Route::post('/admin/class-enrollments/graduate/action', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'storeGraduation'])->name('class-enrollments.store-graduation');
// Menampilkan detail daftar siswa yang telah dialokasikan pada kelas tertentu
Route::get('/admin/class-enrollments/{class_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'show'])->name('class-enrollments.show');
// Menampilkan formulir penambahan siswa (yang belum memiliki kelas) ke kelas tertentu
Route::get('/admin/class-enrollments/{class_id}/add-students', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'addStudents'])->name('class-enrollments.add-students');
// Memproses penyimpanan massal siswa ke dalam kelas tujuan
Route::post('/admin/class-enrollments/{class_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'storeStudents'])->name('class-enrollments.store-students');
// Menampilkan halaman formulir migrasi (Kenaikan Kelas) dari tahun ajaran sebelumnya
Route::get('/admin/class-enrollments/{class_id}/promote', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'promoteStudents'])->name('class-enrollments.promote');
// Memproses migrasi data siswa dari kelas lama ke kelas baru (Kenaikan Kelas)
Route::post('/admin/class-enrollments/{class_id}/promote', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'storePromotion'])->name('class-enrollments.store-promotion');
// Menghapus alokasi kelas seorang siswa (mengembalikan status menjadi belum memiliki kelas)
Route::delete('/admin/class-enrollments/{enrollment_id}', [\App\Http\Controllers\Admin\ClassEnrollmentController::class, 'destroy'])->name('class-enrollments.destroy');
});

// ==========================================
// GRUP RUTE KHUSUS PEMBINA (EKSTRAKURIKULER)
// ==========================================
Route::middleware(['auth', 'role:pembina'])->prefix('pembina')->group(function () {
    // Menampilkan halaman utama dasbor pembina ekstrakurikuler
    Route::get('/dashboard', [\App\Http\Controllers\Pembina\DashboardController::class, 'index'])->name('pembina.dashboard');

    // Menampilkan informasi profil akun pembina
    Route::get('/profile', [\App\Http\Controllers\Pembina\ProfileController::class, 'index'])->name('pembina.profile.index');
    // Memperbarui informasi profil pembina
    Route::put('/profile', [\App\Http\Controllers\Pembina\ProfileController::class, 'update'])->name('pembina.profile.update');

    // Menampilkan daftar ekstrakurikuler yang dibina oleh pembina bersangkutan
    Route::get('/extracurriculars', [\App\Http\Controllers\Pembina\ExtracurricularController::class, 'index'])->name('pembina.extracurriculars.index');
    // Menampilkan detail ekstrakurikuler & daftar anggota siswa untuk penilaian
    Route::get('/extracurriculars/{id}', [\App\Http\Controllers\Pembina\ExtracurricularController::class, 'show'])->name('pembina.extracurriculars.show');
    // Menyimpan / memperbarui nilai dan catatan perkembangan siswa ekstrakurikuler
    Route::post('/extracurriculars/{id}/grade/{registration_id}', [\App\Http\Controllers\Pembina\ExtracurricularController::class, 'updateGrade'])->name('pembina.extracurriculars.grade');
});

// Alias kompatibilitas rute guru
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pembina\DashboardController::class, 'index'])->name('guru.dashboard');
    Route::get('/profile', [\App\Http\Controllers\Pembina\ProfileController::class, 'index'])->name('guru.profile.index');
    Route::put('/profile', [\App\Http\Controllers\Pembina\ProfileController::class, 'update'])->name('guru.profile.update');
    Route::get('/extracurriculars', [\App\Http\Controllers\Pembina\ExtracurricularController::class, 'index'])->name('guru.extracurriculars.index');
    Route::get('/extracurriculars/{id}', [\App\Http\Controllers\Pembina\ExtracurricularController::class, 'show'])->name('guru.extracurriculars.show');
    Route::post('/extracurriculars/{id}/grade/{registration_id}', [\App\Http\Controllers\Pembina\ExtracurricularController::class, 'updateGrade'])->name('guru.extracurriculars.grade');
});

// ==========================================
// GRUP RUTE KHUSUS SISWA
// ==========================================
Route::middleware(['auth', 'role:siswa'])->group(function () {
    // Menampilkan informasi profil akun siswa
    Route::get('/siswa/profile', [\App\Http\Controllers\Siswa\ProfileController::class, 'index'])->name('siswa.profile.index');
    // Memperbarui informasi profil siswa
    Route::put('/siswa/profile', [\App\Http\Controllers\Siswa\ProfileController::class, 'update'])->name('siswa.profile.update');

    // Menampilkan katalog ekstrakurikuler yang dapat diikuti siswa & status nilai
    Route::get('/siswa/extracurriculars', [StudentExtracurricularController::class, 'index'])->name('siswa.extracurriculars.index');
    // Mengirimkan pengajuan pendaftaran ekstrakurikuler
    Route::post('/siswa/extracurricular-registrations', [ExtracurricularRegistrationController::class, 'store'])->name('extracurricular-registrations.store');
    // Membatalkan atau keluar dari ekstrakurikuler yang diikuti
    Route::delete('/siswa/extracurricular-registrations/{id}', [ExtracurricularRegistrationController::class, 'destroy'])->name('extracurricular-registrations.destroy');

    // Menampilkan riwayat pembayaran iuran ekstrakurikuler
    Route::get('/siswa/payments', [StudentPaymentController::class, 'index'])->name('siswa.payments.index');
    // Mengunggah bukti pembayaran iuran ekstrakurikuler
    Route::post('/siswa/payments', [StudentPaymentController::class, 'store'])->name('siswa.payments.store');
});