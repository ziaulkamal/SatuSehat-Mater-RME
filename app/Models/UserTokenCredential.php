<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTokenCredential extends Model
{
        // Nama tabel
    protected $table = 'client_request_credential';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'const_users',
        'token',
    ];

    // Casting atribut ke tipe data yang tepat
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getToken($const_users) {
        return self::where('const_users', $const_users)
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

    public static function totalHit() {
        return self::sum('total_hit');
    }
}
