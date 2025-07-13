<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sold;
use App\Models\User;
use Stripe\Stripe;


class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
       \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook signature verification failed.'], 400);
        }

        // `checkout.session.completed` イベントが発生した場合
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // ユーザーIDをmetadataから取得
            $user = User::where('stripe_customer_id', $session->customer)->first();

            if ($user) {
                
                // 購入完了情報をデータベースに記録
                $sold = new Sold();
                $sold->user_id = $user->id; 
                $sold->item_id = $session->metadata->item_id;
                $sold->sold = 1;
                $sold->method = $session->payment_method_types[0];
                $sold->save();

                session(['purchase_completed' => true]);
            }
        }
        return response()->json(['success' => true]);
    }
}
