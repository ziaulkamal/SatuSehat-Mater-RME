<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckLastLoginStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Periksa apakah user sudah login
        if (session()->has('id')) {
            $lastLoginTime = Session::get('time');
            $currentTime = Carbon::now();

            // Periksa apakah waktu terakhir login sudah lebih dari 30 menit yang lalu
            if ($currentTime->diffInMinutes($lastLoginTime) > 30) {
                // Lakukan logout user
                redirect()->route('logout.auto', ['id' => session('id')]);

            }
        }
    }
}
