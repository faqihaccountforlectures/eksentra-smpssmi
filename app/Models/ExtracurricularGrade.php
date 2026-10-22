<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtracurricularGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'academic_year_id',
        'coach_id',
        'semester',
        'grade',
        'score',
        'notes',
    ];

    /**
     * Relasi ke data pendaftaran ekstrakurikuler siswa.
     * Menggunakan withTrashed() agar histori nilai tetap dapat diakses meskipun siswa telah keluar dari ekstrakurikuler.
     */
    public function registration()
    {
        return $this->belongsTo(ExtracurricularRegistration::class, 'registration_id')->withTrashed();
    }

    /**
     * Relasi ke data tahun ajaran
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /**
     * Relasi ke data pembina/coach yang memberikan penilaian
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
