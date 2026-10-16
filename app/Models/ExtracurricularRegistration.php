<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtracurricularRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'extracurricular_id',
        'academic_year_id',
        'status',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class, 'extracurricular_id');
    }

    /**
     * Relasi ke riwayat nilai ekstrakurikuler per tahun ajaran dan semester
     */
    public function grades()
    {
        return $this->hasMany(ExtracurricularGrade::class, 'registration_id');
    }

    /**
     * Relasi ke riwayat transaksi pembayaran iuran
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'registration_id');
    }
}
