<?php
namespace StayCore;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class Payment {
    private $pdo;
    private $stripeKey;
    private $stripeSecret;

    public function __construct($pdo, $stripeKey, $stripeSecret) {
        $this->pdo = $pdo;
        $this->stripeKey = $stripeKey;
        $this->stripeSecret = $stripeSecret;
        Stripe::setApiKey($stripeSecret);
    }

    public function getPublishableKey() {
        return $this->stripeKey;
    }

    public function createIntent($amount, $currency = 'inr', $metadata = []) {
        try {
            $intent = PaymentIntent::create([
                'amount' => (int)($amount * 100), // cents
                'currency' => $currency,
                'metadata' => $metadata,
                'automatic_payment_methods' => ['enabled' => true],
            ]);
            return ['success' => true, 'client_secret' => $intent->client_secret, 'intent_id' => $intent->id];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function verifyIntent($intentId) {
        try {
            $intent = PaymentIntent::retrieve($intentId);
            return ['success' => true, 'status' => $intent->status, 'amount' => $intent->amount / 100];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}