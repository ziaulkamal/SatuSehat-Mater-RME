<?php

namespace App\Services;

use App\Models\UserClientCredential;
use App\Models\UserTokenCredential;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class OAuth2Client {
    public function token($const_users) {
        $now    = Carbon::now();
        $data   = UserClientCredential::where('const_users', $const_users)->first();
        if (!$data) {
            $statusCode = 500;
            $response   = 'Const ID Tidak Ditemukan';
            return [ $response, $statusCode ];
        }
        $userTokenCredential = UserTokenCredential::where('const_users', $const_users)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$userTokenCredential || $userTokenCredential->created_at->diffInMinutes($now) > 50 || $userTokenCredential->total_hit > 100) {
            $client = new Client();

            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ];
            $options = [
                'form_params' => [
                    'client_id' => $data->client_id,
                    'client_secret' => $data->secret_id,
                    'grant_type' => 'client_credentials'
                ],
                'headers' => $headers
            ];

            // Create session
            $url = 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken';
        } else {
            $response   = $userTokenCredential->token;
            $statusCode = 200;
            $userTokenCredential->increment('total_hit');
            return [ $response, $statusCode ];
        }

        try {
            $res = $client->post($url, $options);
            $contents = json_decode($res->getBody()->getContents());

            $statusCode = $res->getStatusCode();
            $response   = $contents->access_token;

            UserTokenCredential::create([
                'const_users' => $const_users,
                'token'       => $response,
                'total_hit'   => 1
            ]);

            return [ $response, $statusCode ];
        } catch (\Exception $e) {
            $statusCode = 500;
            $response   = 'Const ID Tidak Ditemukan';
            return [ $response, $statusCode ];
        }
    }
}

