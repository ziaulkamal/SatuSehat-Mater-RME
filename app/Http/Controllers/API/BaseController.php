<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdministratorUser;
use App\Services\OAuth2Client;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class BaseController extends Controller
{

    protected $oauth2Client;

    public function __construct(OAuth2Client $oauth2Client)
    {
        $this->oauth2Client = $oauth2Client;
    }

    function token($const_users) {
        [ $response, $statusCode ] = $this->oauth2Client->token($const_users);
        return $response;
    }

    public function call($id, $resources, $token) {
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
        ];

        $url = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/' . $resources . '/' .$id;
        $request = new Request('GET', $url, $headers);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            return [ $response, $statusCode ];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $response = json_decode($e->getResponse()->getBody()->getContents());

            return [ $response, $statusCode ];
        }
    }


}
