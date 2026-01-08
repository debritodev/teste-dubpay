<?php

namespace App\Services\Payment;

use App\Models\PaymentProvider;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Drivers\AsaasService;
use App\Services\Payment\Drivers\StripeService;
use Exception;

class PaymentFactory
{
    public function resolveSlug(string $preference = 'auto'): string
    {
        if ($preference !== 'auto') {
            return $preference;
        }

        // Busca o provider ativo com a menor taxa
        $cheapest = PaymentProvider::where('is_active', true)
            ->orderBy('base_fee_percentage', 'asc')
            ->first();

        return $cheapest ? $cheapest->slug : 'stripe';
    }

    public function make(string $slug): PaymentGatewayInterface
    {
        return match ($slug) {
            'stripe' => new StripeService(),
            'asaas'  => new AsaasService(),
            default  => throw new Exception("Driver not found for [{$slug}]"),
        };
    }
}