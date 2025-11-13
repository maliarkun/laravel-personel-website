<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function show(): View
    {
        return view('account.subscription');
    }
}
