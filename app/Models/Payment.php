<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'month',
        'year',
        'total_amount',
        'payment_method',
        'proof_of_payment',
        'payment_status',
        'verified_by'
    ];

    /**
     * Relasi ke pendaftaran ekstrakurikuler (termasuk yang telah soft-deleted jika siswa keluar)
     */
    public function registration()
    {
        return $this->belongsTo(ExtracurricularRegistration::class, 'registration_id')->withTrashed();
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Accessor untuk mendapatkan data siswa melalui relasi pendaftaran
     */
    public function getStudentAttribute()
    {
        return $this->registration ? $this->registration->student : null;
    }

    /**
     * Accessor untuk mendapatkan data ekskul melalui relasi pendaftaran
     */
    public function getExtracurricularAttribute()
    {
        return $this->registration ? $this->registration->extracurricular : null;
    }

    /**
     * Accessor student_id melalui relasi pendaftaran
     */
    public function getStudentIdAttribute()
    {
        return $this->registration ? $this->registration->student_id : null;
    }

    /**
     * Accessor extracurricular_id melalui relasi pendaftaran
     */
    public function getExtracurricularIdAttribute()
    {
        return $this->registration ? $this->registration->extracurricular_id : null;
    }
}
