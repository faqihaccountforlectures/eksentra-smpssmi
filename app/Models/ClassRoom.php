<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    // Kolom-kolom yang diizinkan untuk diisi secara massal via form
    protected $fillable = [
        'name',
        'grade_level',
    ];

    // Relasi ke tabel class_enrollments.
    // Ibaratnya, ini tuh buat nyari "Siapa aja sih anak-anak yang terdaftar dan duduk di kelas ini?"
    // Lewat sini kita bisa ngambil semua histori pendaftaran kelas (enrollments) yang nyangkut ke ID kelas ini.
    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class, 'class_room_id');
    }


}
