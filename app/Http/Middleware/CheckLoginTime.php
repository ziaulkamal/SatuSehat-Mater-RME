<?php

namespace App\Http\Middleware;

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
        if (Auth::check()) {
            $lastLoginTime = session('time');
            $currentTime = Carbon::now();

            // Periksa apakah waktu terakhir login sudah lebih dari 30 menit yang lalu
            if ($currentTime->diffInMinutes($lastLoginTime) > 30) {
                // Lakukan logout user
                Auth::logout();

                // Redirect ke halaman login atau lakukan tindakan lain
                return redirect('/login')->with('message', 'Waktu sesi telah habis. Silakan login kembali.');
            }
        }

        return $next($request);
    }
}

