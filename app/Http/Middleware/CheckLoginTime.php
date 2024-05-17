<?php

namespace App\Http\Middleware;

use App\Models\AdministratorUser;
use App\Services\TelegramService;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckLoginTime
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function handle($request, Closure $next)
    {
        // Memeriksa apakah waktu login tersimpan di sesi
        if (session()->has('login_time')) {
            $loginTime = session('login_time');
            $id = session('user_id');
            $currentTime = Carbon::now();

            // Memeriksa jika sudah lebih dari 1 menit
            if ($currentTime->diffInMinutes($loginTime) >= 1) {


                // Memperbarui status dan kondisi di model
                $user = AdministratorUser::find($id);
                if ($user) {
                    $user->condition = false;
                    $user->token = null;
                    $user->save();
                }


                $message = "[EXPIRED]\n\n$user";
                $this->telegramService->sendMessage($message);

                // Menghapus sesi
                session()->flush();

                return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
            }
        }

        return $next($request);
    }
}

