<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;

class GatewayClientController extends BaseController
{
   function test() {
        $url = 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials';
        $fields = [
            'client_id' => '37ISDRZj9EBUf9WPujky7HY2S6gKADpnLKqzuDdf63Vrnphc',
            'client_secret' => 'QuqhjY8aK6COUnItyAl5EJKdyOTiE5pADg44GCtxtxvrefmdOU5wnd6S2WSYhzt3',
        ];

        $fields_string = http_build_query($fields);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        // Execute cURL session and fetch response
        $result = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            dd('cURL error: ' . $error_msg);
        }

        // Get HTTP status code
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close cURL session
        curl_close($ch);

        // Decode the response
        $response = json_decode($result, true);

        if ($http_status == 200) {
            // Access token retrieved successfully
            $access_token = $response['access_token'];
            dd($response);
        } else {
            // Error response
            dd('HTTP Status: ' . $http_status, $response);
        }
   }
}
