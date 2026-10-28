<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relasi ke akun User pelaku aksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper statis untuk mencatat aksi penting secara instan.
     */
    public static function record(string $action, string $description, ?User $user = null): self
    {
        $actor = $user ?? Auth::user();

        return static::create([
            'user_id'     => $actor?->id,
            'role'        => $actor?->role,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
