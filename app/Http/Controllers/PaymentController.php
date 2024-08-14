<?php

namespace App\Http\Controllers;

use App\Providers\StripeServiceProvider;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends Controller
{

    public UserRepositoryInterface $userRepository;
    public OrderRepositoryInterface $orderRepository;
    public StripeServiceProvider $stripeServiceProvider;

    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->orderRepository = app(OrderRepositoryInterface::class);
        $this->stripeServiceProvider = app(StripeServiceProvider::class);
    }
    public function index()
    {
        $this->stripeServiceProvider->createUserSlotsPayment();
    }

    public function success(Request $request)
    {

        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET_KEY"));
        $session = \Stripe\Checkout\Session::retrieve($request->get("session_id"));
        $customer = \Stripe\Customer::retrieve($session->customer);

        if (!$session) {
            throw new NotFoundHttpException();
        }

        $order = $this->orderRepository->getBySessionId($session->id);

        if (!$order) {
            throw new NotFoundHttpException();
        }
        if ($order && $order->is_paid == false) {
            $this->orderRepository->increaseUserFriendLimit($order);
            $this->orderRepository->changeStatus($order, true);
        }

        return view("stripe_payment.success", compact('customer'));
    }

    public function cancel()
    {
        return view("stripe_payment.cancel");
    }

    public function webhook()
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET_KEY');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $sessionId = $event->data->object->id;

                $order = $this->orderRepository->getBySessionId($sessionId);
                if ($order && $order->is_paid == false) {
                    $this->orderRepository->increaseUserFriendLimit($order);
                    $this->orderRepository->changeStatus($order, true);
                }

            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }
}
