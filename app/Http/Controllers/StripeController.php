<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\User;
use App\Models\ShopSession;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\API\ShopSessionController;
use App\Notifications\OrderConfirmationNotification;

class StripeController extends Controller
{
    public function checkout(ShopSession $shopSession)
    {
        if (Auth::user()->id === $shopSession->user_id) {

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $shop = new ShopSessionController();
            $cart = $shop->show($shopSession->user_id);

            $products = json_decode($cart->content());
            $productId = $products->data->products;

            $lineItems = [];

            foreach ($productId as $item) {
                $lineItems[] = [
                    'price' => $item->stripe_price,
                    'adjustable_quantity' => [
                        'enabled' => true,
                        'minimum' => 1,
                        'maximum' => 10,
                    ],
                    'quantity' => 1,
                ];
            };

            $session = $stripe->checkout->sessions->create([
                'line_items' => [
                    [
                        $lineItems
                    ]
                ],
                'shipping_address_collection' => ['allowed_countries' => ['FR']],
                'shipping_options' => [
                    [
                        'shipping_rate_data' => [
                            'type' => 'fixed_amount',
                            'fixed_amount' => [
                                'amount' => 490,
                                'currency' => 'eur',
                            ],
                            'display_name' => 'Mondial relay',
                            'delivery_estimate' => [
                                'minimum' => [
                                    'unit' => 'business_day',
                                    'value' => 4,
                                ],
                                'maximum' => [
                                    'unit' => 'business_day',
                                    'value' => 6,
                                ],
                            ],
                        ],
                    ],
                    [
                        'shipping_rate_data' => [
                            'type' => 'fixed_amount',
                            'fixed_amount' => [
                                'amount' => 680,
                                'currency' => 'eur',
                            ],
                            'display_name' => 'Colissimo',
                            'delivery_estimate' => [
                                'minimum' => [
                                    'unit' => 'business_day',
                                    'value' => 2,
                                ],
                                'maximum' => [
                                    'unit' => 'business_day',
                                    'value' => 2,
                                ],
                            ],
                        ],
                    ],
                ],
                'custom_text' => [
                    'shipping_address' => [
                        'message' => 'Adresse de livraison',
                    ],
                ],
                'custom_fields' => [
                    [
                        'key' => 'engraving',
                        'label' => [
                            'type' => 'custom',
                            'custom' => 'Autre demande',
                        ],
                        'optional' => true,
                        'type' => 'text',
                    ],
                ],
                'mode' => 'payment',
                'success_url' => 'http://localhost:3000/endpoint/succes',
            ]);

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'data' =>  $session,
            ]);
        } else {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'erreur',
            ]);
        }
    }

    public function webhook()
    {
        // The library needs to be configured with your account's secret key.
        // Ensure the key is kept out of any version control system you might be using.
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_418b0325075e8e6af8a643faf54b88c587d69aa8e9f49d4e3e958c178400680b';

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
            echo json_encode(['Error parsing payload: ' => $e->getMessage()]);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            echo json_encode(['Error verifying webhook signature: ' => $e->getMessage()]);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntentSucceeded = $event->data->object; // contains a \Stripe\PaymentIntent
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object;
                break;
                // ... handle other event types
            case 'checkout.session.completed':

                $session = $event->data->object;

                $customerEmail = $session->customer_details->email;

                // Mail::to($customerEmail)->send(new OrderConfirmationMail($session));

                $user = User::where('email', $customerEmail)->first();

                $user->notify(new OrderConfirmationNotification($session));

                break;
            case 'payment_intent.created':
                $paymentIntentCreated = $event->data->object;
                break;

            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }
}
