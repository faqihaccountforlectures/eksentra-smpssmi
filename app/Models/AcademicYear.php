<?php

namespace App\Models;

use Carbon\Carbon;
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
     * Tanggal mulai tahun ajaran: 1 Juli tahun pertama.
     * Contoh: "2026/2027" → 2026-07-01
     */
    public function startDate(): Carbon
    {
        $startYear = (int) explode('/', $this->year_name)[0];
        return Carbon::create($startYear, 7, 1)->startOfMonth();
    }

    /**
     * Tanggal selesai tahun ajaran: 30 Juni tahun kedua.
     * Contoh: "2026/2027" → 2027-06-30
     */
    public function endDate(): Carbon
    {
        $endYear = (int) explode('/', $this->year_name)[1];
        return Carbon::create($endYear, 6, 1)->endOfMonth();
    }

    /**
     * Relasi ke riwayat penilaian ekstrakurikuler pada tahun ajaran ini
     */
    public function extracurricularGrades()
    {
        return $this->hasMany(ExtracurricularGrade::class, 'academic_year_id');
    }
}
