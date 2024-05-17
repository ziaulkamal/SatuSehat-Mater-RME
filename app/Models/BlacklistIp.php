<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistIp extends Model
{
    protected $table = 'blacklist_ips';
    protected $fillable = [
        'ip',
        'ua', // Jika diperlukan, tambahkan atribut lain ke dalam fillable property
    ];
}
