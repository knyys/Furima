<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Sold;
use App\Models\Item;
use App\Models\Profile;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // StripeのWebhook Secret
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        // 署名検証
        $sig_header = $request->header('Stripe-Signature');
        $payload = $request->getContent();

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe Webhook Signature Verification Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // イベントごとの処理
        if ($event->type === 'checkout.session.completed' || $event->type === 'checkout.session.async_payment_succeeded') {
            $session = $event->data->object;
            $this->processPaymentSuccess($session);
        }

        return response()->json(['status' => 'success']);
    }

    private function processPaymentSuccess($session)
    {
        $item_id = $session->metadata->item_id;
        $user_id = $session->metadata->user_id;
        $method = $session->payment_method_types[0];

        // ユーザーのプロフィール情報取得
        $profile = Profile::where('user_id', $user_id)->first();

        if (!$profile) {
            Log::error("Profile not found for user_id: $user_id");
            return;
        }

        // 購入商品取得
        $item = Item::find($item_id);
        if (!$item) {
            Log::error("Item not found for item_id: $item_id");
            return;
        }

        // Soldテーブルに登録（トランザクション管理）
        DB::transaction(function () use ($user_id, $item_id, $method, $profile) {
            Sold::create([
                'user_id' => $user_id,
                'item_id' => $item_id,
                'sold' => 1,
                'method' => $method,
                'address_number' => $profile->address_number,
                'address' => $profile->address,
                'building' => $profile->building,
            ]);
        });

        Log::info("Sold record created: User ID: $user_id, Item ID: $item_id, Payment Method: $method");


    }

}
