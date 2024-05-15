<?php

namespace App\Http\Controllers;

use App\Models\TransactionPayment;
use App\Models\UserBillingPayment;
use App\Models\UserClientCredential;
use App\Models\UserTokenCredential;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function countUserActive() {
        return UserClientCredential::getClientActive()->count();
    }

    public function countDebitTransaction() {
        return TransactionPayment::sumTotalBayar();
    }

    public function countHitEndpoint() {
        return UserTokenCredential::totalHit();
    }

    public function countUserSuspend() {
        return UserBillingPayment::suspendUser()->count();
    }

    public function dataUserSuspend() {
        return UserBillingPayment::suspendUser();
    }

    public function dataUserTransaction() {
        return TransactionPayment::lastUpdates();
    }
}
