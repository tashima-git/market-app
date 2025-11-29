<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    /**
     * Stripe Webhook を受信して purchases に反映
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        // リクエスト生データをログに出力
        Log::info('[StripeWebhook] Raw payload: ' . $payload);

        try {
            // Stripe イベントの署名検証
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            Log::info('[StripeWebhook] Event type: ' . $event->type);

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                // Session の metadata を取得
                $metadata = (array) ($session->metadata ?? []);
                Log::info('[StripeWebhook] Session metadata:', $metadata);

                // PaymentIntent ID から metadata を補完（足りない場合）
                $paymentIntentId = $session->payment_intent ?? null;
                if ($paymentIntentId) {
                    Stripe::setApiKey(env('STRIPE_SECRET'));
                    $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

                    if (isset($paymentIntent->metadata)) {
                        $metadata = array_merge($metadata, $paymentIntent->metadata->toArray());
                        Log::info('[StripeWebhook] PaymentIntent metadata merged:', $metadata);
                    }
                }

                // 必須データを抽出
                $itemId = $metadata['item_id'] ?? null;
                $userId = $metadata['user_id'] ?? null;
                $paymentMethod = $metadata['payment_method'] ?? 'card';
                $postal = $metadata['postal_code'] ?? null;
                $address = $metadata['address'] ?? null;
                $building = $metadata['building_name'] ?? null;

                // 必須データの確認
                if (!$itemId || !$userId) {
                    Log::warning('[StripeWebhook] Missing required metadata.', ['metadata' => $metadata]);
                    return response()->json(['status' => 'missing metadata'], 400);
                }

                // 重複購入チェック
                if (!Purchase::where('item_id', $itemId)->exists()) {
                    // 購入データを作成
                    $purchase = Purchase::create([
                        'item_id' => $itemId,
                        'user_id' => $userId,
                        'payment_method' => $paymentMethod,
                        'sending_postcode' => $postal,
                        'sending_address' => $address,
                        'sending_building' => $building,
                    ]);

                    Log::info('[StripeWebhook] Purchase created:', ['purchase_id' => $purchase->id]);

                    // 商品を「sold」に更新
                    $item = Item::find($itemId);
                    if ($item) {
                        $item->update(['status' => 'sold']);
                        Log::info("[StripeWebhook] Item {$itemId} marked as SOLD.");
                    } else {
                        Log::warning("[StripeWebhook] Item not found: {$itemId}");
                    }
                } else {
                    Log::warning("[StripeWebhook] Purchase for item {$itemId} already exists. Skipping.");
                }
            }

            return response()->json(['status' => 'success'], 200);

        } catch (UnexpectedValueException $e) {
            Log::error('[StripeWebhook] Invalid payload: ' . $e->getMessage());
            return response()->json(['status' => 'invalid payload'], 400);

        } catch (SignatureVerificationException $e) {
            Log::error('[StripeWebhook] Invalid signature: ' . $e->getMessage());
            return response()->json(['status' => 'invalid signature'], 400);

        } catch (\Exception $e) {
            Log::error('[StripeWebhook] Unexpected error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }
}
