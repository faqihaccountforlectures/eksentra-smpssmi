<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Kolom-kolom di database yang boleh diisi secara massal (misal pake metode create atau update)
    protected $fillable = [
        'name',         // Nama Lengkap Pengguna (Admin/Pengguna Sistem)
        'email',        // Email / Username untuk Login
        'phone_number', // Nomor Telepon / WhatsApp
        'password',
        'google_id',    // Buat nyimpen ID dari Google SSO
        'role',         // Role pengguna: admin, coach, atau siswa
    ];

    // Kolom yang disembunyiin pas datanya diambil (biar password gak bocor di respon API/JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Otomatis ngubah (casting) data password jadi bentuk hash (enkripsi) sebelum disimpen ke database
    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi One-to-One: Nentuin kalo satu Akun User punya satu Profil Siswa.
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    // Relasi One-to-One: Profil Coach (coachProfile)
    public function coachProfile(): HasOne
    {
        return $this->hasOne(CoachProfile::class);
    }

    /**
     * Relasi ke kegiatan ekstrakurikuler yang dibina oleh coach ini.
     */
    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class, 'coach_id');
    }

    /**
     * Relasi ke data enrollment kelas siswa
     */
    public function classEnrollments()
    {
        return $this->hasMany(ClassEnrollment::class, 'student_id');
    }

    /**
     * Helper untuk mendapatkan nama kelas siswa pada tahun ajaran tertentu
     */
    public function getClassNameForYear($academicYearId = null): string
    {
        $enrollment = $this->classEnrollments
            ? ($academicYearId ? $this->classEnrollments->firstWhere('academic_year_id', $academicYearId) : $this->classEnrollments->first())
            : null;

        if (!$enrollment) {
            $query = $this->classEnrollments();
            if ($academicYearId) {
                $query->where('academic_year_id', $academicYearId);
            }
            $enrollment = $query->with('classRoom')->latest('id')->first();
        }

        return $enrollment && $enrollment->classRoom ? $enrollment->classRoom->name : '-';
    }

    /**
     * FUNGSI KODE: Accessor untuk mendapatkan nama lengkap tampilan pengguna.
     * Secara cerdas memeriksa profil pengguna sesuai peran:
     * - Jika Coach: mengambil nama dari coachProfile->full_name.
     * - Jika Siswa: mengambil nama dari studentProfile->full_name.
     * - Jika Admin atau profil belum ada: menggunakan kolom 'name' atau potongan awal email.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->role === 'coach') {
            $profile = $this->relationLoaded('coachProfile') ? $this->coachProfile : $this->coachProfile()->first();
            if ($profile && !empty($profile->full_name)) {
                return $profile->full_name;
            }
        }

        if ($this->role === 'siswa') {
            $profile = $this->relationLoaded('studentProfile') ? $this->studentProfile : $this->studentProfile()->first();
            if ($profile && !empty($profile->full_name)) {
                return $profile->full_name;
            }
        }

        return !empty($this->name) ? $this->name : explode('@', $this->email)[0];
    }

    /**
     * FUNGSI KODE: Accessor untuk mendapatkan 1 karakter inisial nama depan pengguna
     * untuk dirender sebagai foto profil lingkaran (avatar placeholder) di topbar.
     */
    public function getInitialAttribute(): string
    {
        $name = trim($this->display_name);
        return strtoupper(mb_substr($name, 0, 1, 'UTF-8')) ?: 'U';
    }
}