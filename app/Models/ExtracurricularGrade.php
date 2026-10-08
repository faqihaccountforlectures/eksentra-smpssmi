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
        'semester',
        'grade',
        'score',
        'notes',
    ];

    /**
     * Relasi ke data pendaftaran ekstrakurikuler siswa
     */
    public function registration()
    {
        return $this->belongsTo(ExtracurricularRegistration::class, 'registration_id');
    }

    /**
     * Relasi ke data tahun ajaran
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
