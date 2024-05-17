<?php

namespace App\Models;

use App\Models\UserClientCredential;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBillingPayment extends Model
{
    // Nama tabel
    protected $table = 'billing_payment_due';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'const_users',
        'tanggal_mulai',
        'jenis_fasyankes',
        'harga_awal',
        'harga_langganan',
        'total_bayar',
        'status',
        'jatuh_tempo',
    ];


    // Casting atribut ke tipe data yang tepat
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = ['deleted_at', 'jatuh_tempo', 'tanggal_mulai'];


    public function getJatuhTempoAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setJatuhTempoAttribute($value)
    {
        $this->attributes['jatuh_tempo'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    // Aksesornya untuk mengubah format tanggal tanggal_mulai
    public function getTanggalMulaiAttribute($value)
    {
        // Ubah format tanggal menggunakan Carbon
        $carbonDate = Carbon::createFromFormat('Y-m-d', $value);

        // Format ke "Tanggal Bulan Tahun" dalam bahasa Indonesia
        return $carbonDate->translatedFormat('d F Y');
    }
    /**
     * Get the client that owns the UserBillingPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(UserClientCredential::class, 'const_users', 'const_users');
    }

    public static function getAll($perPage) {
        // Mendapatkan data UserBillingPayment dengan hasil paginasi
        $userBillingPayments = self::orderBy('updated_at', 'desc')->paginate($perPage);

        // Memuat klien terkait untuk setiap UserBillingPayment
        $userBillingPayments->load('client');

        return $userBillingPayments;
    }


    public static function suspendUser() {
        $user = self::where('status', 'suspend')
                ->orderBy('jatuh_tempo', 'asc')
                ->take(5)
                ->get();

        $user->load('client');

        return $user;
    }


    public static function detail($const_users) {
        $data =  self::where('const_users', $const_users)->first();
        $data->load('client');

        return $data;
    }

    public static function actionStatus($const_users, $status) {
        switch ($status) {
            case 'active':
                $code = 'active';
                break;

            case 'suspend':
                $code = 'suspend';
                break;
        }
        $update = self::where('const_users', $const_users)->first();
        $update->status = $code;

        return $update->save();

    }

}
