<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{

    public function __construct(){}

    public function createUserSlotsPayment()
    {
        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));
        $friendSlotsProductId = 'price_1PlWbfKGTv6RIJeFxPtgen96';

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price' => $friendSlotsProductId,
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('payment.cancel', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
            'customer_creation' => 'always',
        ]);

        Order::create([
            'is_paid' => false,
            'total_price' => 5,
            'session_id' => $checkout_session->id,
            'user_id' => \Auth::user()->id,
        ]);

        return redirect()->to($checkout_session->url)->send();
    }
}
