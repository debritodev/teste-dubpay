<?php
declare(strict_types=1);
namespace App\Services\Payment\Contracts;
use App\Services\Payment\DTOs\PaymentOutput;

interface PaymentGatewayInterface {
    public function charge(int $amountInCents, array $data): PaymentOutput;
}