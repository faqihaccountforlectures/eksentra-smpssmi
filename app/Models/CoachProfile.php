<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachProfile extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'coach_profiles';

    // Daftar kolom yang dapat diisi secara massal (mass assignment)
    protected $fillable = [
        'user_id',
        'full_name',
        'gender',
        'phone_number',
    ];

    /**
     * Relasi balik (Inverse One-to-One) ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
