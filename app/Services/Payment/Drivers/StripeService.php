<?php
declare(strict_types=1);
namespace App\Services\Payment\Drivers;
use App\Enums\PaymentStatus;
use App\Services\Payment\DTOs\PaymentOutput;
use App\Services\Payment\Contracts\PaymentGatewayInterface;

class StripeService implements PaymentGatewayInterface {
    public function charge(int $amountInCents, array $data): PaymentOutput {
        $transactionId = 'ch_' . uniqid();
        return new PaymentOutput(true, $transactionId, PaymentStatus::PAID, ['gateway' => 'stripe']);
    }
}