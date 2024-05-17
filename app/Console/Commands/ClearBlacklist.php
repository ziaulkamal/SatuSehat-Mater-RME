<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearBlacklist extends Command
{
    protected $signature = 'blacklist:clear';
    protected $description = 'Clear IP blacklist';

    public function handle()
    {
        $keys = Cache::get('blacklist_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('blacklist_keys');
        $this->info('Blacklist cleared!');
    }
}
