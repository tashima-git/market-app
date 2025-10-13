<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        // まず raw payload をログに出力
        Log::info('[StripeWebhook] Raw payload: ' . $payload);

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            Log::info('[StripeWebhook] Event Type: ' . $event->type);

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;

                // metadata が存在するかを確認しログに出力
                $metadata = (array) ($session->metadata ?? []);
                Log::info('[StripeWebhook] Session metadata:', $metadata);

                $itemId = $metadata['item_id'] ?? null;
                $userId = $metadata['user_id'] ?? null;
                $paymentMethod = $metadata['payment_method'] ?? 'card';
                $postal = $metadata['postal_code'] ?? null;
                $address = $metadata['address'] ?? null;
                $building = $metadata['building_name'] ?? null;

                if (!$itemId || !$userId) {
                    Log::warning('[StripeWebhook] Missing required metadata.', ['metadata' => $metadata]);
                    return response()->json(['status' => 'missing metadata'], 400);
                }

                if (!Purchase::where('item_id', $itemId)->exists()) {
                    $purchase = Purchase::create([
                        'item_id' => $itemId,
                        'user_id' => $userId,
                        'payment_method' => $paymentMethod,
                        'sending_postcode' => $postal,
                        'sending_address' => $address,
                        'sending_building' => $building,
                    ]);

                    Log::info('[StripeWebhook] Purchase created:', ['purchase_id' => $purchase->id]);

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
