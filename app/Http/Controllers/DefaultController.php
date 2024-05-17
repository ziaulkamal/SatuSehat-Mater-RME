<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ComponentController;
use Illuminate\Http\Request;

class DefaultController extends ComponentController
{

    function index() {
        $data = [
            'title'     => 'Dashboard',
            'page'      => 'pages.dashboard',
            'data'      => [
                'user_active'    => $this->countUserActive(),
                'total_debit'    => $this->countDebitTransaction(),
                'hit_endpoint'   => $this->countHitEndpoint(),
                'total_suspend'  => $this->countUserSuspend(),
            ],
            'table'     => [
                'suspend_user'   => $this->dataUserSuspend(),
                'transac_user'   => $this->dataUserTransaction(),
            ]
        ];
        // dd($data);
        return $this->layout($data);
    }

    function fasyankes() {
        $data = [
            'title'     => 'Data Fasilitas Layanan Kesehatan',
            'page'      => 'pages.fasyankes.index',
            'data'      => []
        ];

        return $this->layout($data);
    }

    function billing() {
        $data = [
            'title'     => 'Data Billing',
            'page'      => 'pages.billing.index',
            'data'      => []
        ];

        return $this->layout($data);
    }

    function payment() {
        $data = [
            'title'     => 'Detail History Pembayaran',
            'page'      => 'pages.billing.detail',
            'data'      => []
        ];

        return $this->layout($data);
    }

    function layout($data) {
        return view($data['page'])->with($data);
    }

    function test() : Returntype {
        dd(session()->all());
    }
}
