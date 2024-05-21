<?php

namespace App\Http\Middleware;

use App\Jobs\CheckLastLoginStatus;

use App\Models\AdministratorUser;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckLoginTime
{

   public function handle($request, Closure $next)
    {
        // Periksa apakah user sudah login
        if (session()->has('id')) {
            $lastLoginTime = session('time');
            $currentTime = Carbon::now();

            // Periksa apakah waktu terakhir login sudah lebih dari 30 menit yang lalu
            if ($currentTime->diffInMinutes($lastLoginTime) > 30) {
                // Lakukan logout user
                return redirect()->route('logout.auto', ['id' => session('id')]);

            }
        }

        // Jadwalkan pekerjaan antrian CheckLastLoginStatus untuk memeriksa waktu terakhir login secara teratur
        CheckLastLoginStatus::dispatch();

        return $next($request);
    }
}

