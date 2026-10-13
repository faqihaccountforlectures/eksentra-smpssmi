<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembinaProfile extends Model
{
    use HasFactory;

    protected $table = 'teacher_profiles';

    // Daftar kolom yang bisa diisi sekalian (mass assignment)
    protected $fillable = [
        'user_id',
        'nip',
        'teacher_code',
        'full_name',
        'position',
        'gender',
        'phone_number',
    ];

    /**
     * Accessor & Mutator pembina_code agar dapat diakses menggunakan nama pembina_code maupun teacher_code
     */
    public function getPembinaCodeAttribute()
    {
        return $this->attributes['teacher_code'] ?? null;
    }

    public function setPembinaCodeAttribute($value)
    {
        $this->attributes['teacher_code'] = $value;
    }

    // Relasi balik (Inverse) ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
