<?php

namespace App\Models;

use App\Models\UserClientCredential;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    protected $table = 'transaction_payment';

    protected $fillable = [
        'transaction_id',
        'const_users',
        'total_bayar',
        'status',
        'created_at'
    ];

    protected $cast = ['created_at'];

    protected $dates = ['created_at'];

    public static function sumTotalBayar()
    {
        $total = self::sum('total_bayar');
        return self::formatRupiah($total);
    }

    private static function formatRupiah($amount)
    {
        return 'IDR ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Get the client that owns the TransactionPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(UserClientCredential::class, 'const_users', 'const_users');
    }


    public static function lastUpdates() {
        $data = self::orderBy('created_at', 'desc')
                ->take(10)
                ->get();

        $data->load('client');
        return $data;
    }

    public static function detail($const_users) {
        $data = self::where('const_users', $const_users)
                ->orderBy('created_at', 'desc')
                ->get();

        $data->load('client');

        return $data;
    }

    // Mutator to set the 'created_at' attribute in the desired format
    public function getCreatedAtAttribute($value)
    {
        // Convert the incoming date string to Carbon instance
        $carbonDate = Carbon::parse($value);

        // Format the Carbon instance as required (dd-mm-yyyy)
        $formattedDate = $carbonDate->format('d M Y');

        // Return the formatted date
        return $formattedDate;
    }
}
