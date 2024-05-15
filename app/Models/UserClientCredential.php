<?php

namespace App\Models;

use App\Models\UserBillingPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UserClientCredential extends Model
{
    use HasFactory, SoftDeletes;
    // Nama tabel
    protected $table = 'user_client_credential';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'organisasi_id',
        'client_id',
        'secret_id',
    ];

    // Jika ada kolom yang tidak ingin dimasukkan ke dalam array JSON
    protected $hidden = [
        'secret_id',
    ];

    // Casting atribut ke tipe data yang tepat
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    public static function generateUUID()
    {
        return Str::uuid()->toString();
    }

    protected static function booted()
    {
        static::creating(function ($userClientCredential) {
            // Mengisi const_users dengan UUID
            $userClientCredential->const_users = self::generateUUID();
        });
    }

    public static function data($const_users) {
        return self::where('const_users', $const_users)->first();
    }

    /**
     * Get the token that owns the UserClientCredential
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function token()
    {
        return $this->belongsTo(UserTokenCredential::class, 'const_users', 'const_users');
    }


    public static function getAll($perPage = 10)
    {
        return self::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public static function getClientActive() {
       return self::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getActive()
    {
        // Subquery untuk mendapatkan const_users yang sudah ada di UserBillingPayment
        $usedUsers = UserBillingPayment::select('const_users')->pluck('const_users');

        // Mengambil data UserClientCredential yang aktif dan tidak ada di usedUsers
        return self::where('status', 'active')
            ->whereNotIn('const_users', $usedUsers)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getStatusClient($const_users) {
        $billing = UserBillingPayment::where('const_users', $const_users)
                ->where('status', 'active')
                ->get();
        $client = self::where('const_users', $const_users)
                ->where('status', 'active')
                ->get();

        if ($billing->count() === 1 && $client->count() === 1) {
            return true;
        }else {
            return false;
        }
    }
}
