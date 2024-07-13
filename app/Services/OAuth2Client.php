<?php

namespace App\Services;

use App\Models\UserClientCredential;
use App\Models\UserTokenCredential;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class OAuth2Client {

    public function token($const_users) {

        try {
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
                    ],
                ];

                // Create session
                $url        = 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials';
                $request    = new Request('POST', $url, $headers);

                $res        = $client->sendAsync($request, $options)->wait();
                $contents   = json_decode($res->getBody()->getContents());

                $statusCode = $res->getStatusCode();
                $response   = $contents->access_token;

                dd([ $response, $statusCode ]);
                UserTokenCredential::create([
                    'const_users' => $const_users,
                    'token'       => $response,
                    'total_hit'   => 1
                ]);

                return [ $response, $statusCode ];
            }else {
                $response   = $userTokenCredential->token;
                $statusCode = 200;
                $userTokenCredential->increment('total_hit');
                return [ $response, $statusCode ];
            }

        } catch (\Exception $e) {
            $statusCode = 500;
            $response   = 'Const ID Tidak Ditemukan';
            return [ $response, $statusCode ];
        }

    }
}
