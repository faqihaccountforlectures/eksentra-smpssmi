<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    // Nentuin kolom mana aja yang boleh diisi lewat form (Mass Assignment)
    protected $fillable = [
        'year_name',
        'is_active',
    ];

    /**
     * Relasi ke riwayat penilaian ekstrakurikuler pada tahun ajaran ini
     */
    public function extracurricularGrades()
    {
        return $this->hasMany(ExtracurricularGrade::class, 'academic_year_id');
    }
}
