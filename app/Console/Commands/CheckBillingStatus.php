<?php

namespace App\Console\Commands;

use App\Models\UserBillingPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckBillingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-billing-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengatur Jadwal Jatuh Tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions = UserBillingPayment::where('status', 'active')->get();

        foreach ($subscriptions as $subscription) {
            if (Carbon::now()->gt(Carbon::parse($subscription->jatuh_tempo))) {
                $subscription->status = 'suspend';
                $subscription->save();
            }
        }

        $this->info('Billing statuses updated successfully.');
    }
}
