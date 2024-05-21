<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AdministratorUser;
use Carbon\Carbon;

class UserStatusCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Ambil semua administrator user yang waktu update-nya lebih dari 30 menit yang lalu
        $users = AdministratorUser::where('updated_at', '<', Carbon::now()->subMinutes(30))->get();

        foreach ($users as $user) {
            $user->update(['condition' => false]); // Rubah condition menjadi false
        }
    }
}
