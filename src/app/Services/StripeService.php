<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService
{
    public function createCheckoutSession(array $params)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        return Session::create($params);
    }
}
