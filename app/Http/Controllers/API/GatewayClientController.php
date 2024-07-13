<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class GatewayClientController extends BaseController
{
   function test() {
        $client = new Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $options = [
            'form_params' => [
                'client_id' => '37ISDRZj9EBUf9WPujky7HY2S6gKADpnLKqzuDdf63Vrnphc',
                'client_secret' => 'QuqhjY8aK6COUnItyAl5EJKdyOTiE5pADg44GCtxtxvrefmdOU5wnd6S2WSYhzt3',
            ],
        ];

        // Create session
        $url        = 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials';
        $request    = new Request('POST', $url, $headers);

        $res        = $client->sendAsync($request, $options)->wait();
        $contents   = json_decode($res->getBody()->getContents());

        $statusCode = $res->getStatusCode();
        $response   = $contents->access_token;
        dd($contents);
   }
}
