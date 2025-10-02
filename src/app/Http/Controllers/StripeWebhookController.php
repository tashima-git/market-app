<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Item;

class StripeWebhookController extends Controller
{
    /**
     * Stripe Webhook を受信
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                $metadata = $session->metadata;

                // Purchase 作成
                Purchase::create([
                    'item_id' => $metadata->item_id,
                    'user_id' => $metadata->user_id,
                    'payment_method' => $metadata->payment_method,
                    'sending_postcode' => $metadata->postal_code,
                    'sending_address' => $metadata->address,
                    'sending_building' => $metadata->building_name,
                ]);

                // 商品ステータス更新
                Item::find($metadata->item_id)->update(['status' => 'sold']);
            }

            return response()->json(['status' => 'success']);

        } catch (\UnexpectedValueException $e) {
            return response()->json(['status' => 'invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['status' => 'invalid signature'], 400);
        }
    }
}
