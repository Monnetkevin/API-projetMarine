<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function session(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }
}
