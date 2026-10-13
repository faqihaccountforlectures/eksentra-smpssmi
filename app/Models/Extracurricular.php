<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'schedule',
        'coach_id',
        'fee'
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function registrations()
    {
        return $this->hasMany(ExtracurricularRegistration::class, 'extracurricular_id');
    }
}
