<?php

namespace App\Http\Middleware;

use App\Models\BlacklistIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class DetectBruteForce
{
    protected $maxAttempts = 30;
    protected $decayMinutes = 30;



    public function handle(Request $request, Closure $next)
    {
        $ip = file_get_contents("http://ipecho.net/plain");
        $cacheKey = 'attempts_' . $ip;

        // Cek apakah IP sudah dalam blacklist
        if (Cache::has('blacklist_' . $ip)) {
            return response('Your IP is blacklisted.', Response::HTTP_FORBIDDEN);
        }

        // Jika tidak, cek apakah IP ada dalam database
        $blacklistIp = BlacklistIp::where('ip', $ip)->first();

        if ($blacklistIp) {
            // Jika sudah, tambahkan IP ke dalam cache blacklist
            Cache::put('blacklist_' . $ip, true, now()->addMinutes($this->decayMinutes));
            return response('Your IP is blacklisted.', Response::HTTP_FORBIDDEN);
        }

        // Jika IP tidak dalam blacklist atau database, lanjutkan penghitungan percobaan
        $attempts = Cache::increment($cacheKey);

        if ($attempts > $this->maxAttempts) {
            $method = $request->method();
            $path = $request->path();

            // Jika mencapai batas, tambahkan IP ke dalam database dan blacklist
            BlacklistIp::create(['ip' => $ip, 'ua' => $request->header('User-Agent')]);
            Cache::put('blacklist_' . $ip, true, now()->addMinutes($this->decayMinutes));
            return response('Your IP is blacklisted.', Response::HTTP_FORBIDDEN);
        }

        // Jika ini percobaan pertama, atur cache untuk IP
        if ($attempts === 1) {
            Cache::put($cacheKey, 1, now()->addMinutes($this->decayMinutes));
        }

        return $next($request);
    }
}
