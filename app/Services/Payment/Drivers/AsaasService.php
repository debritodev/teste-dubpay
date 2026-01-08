<?php
declare(strict_types=1);
namespace App\Services\Payment\Drivers;

use App\Enums\PaymentStatus;
use App\Services\Payment\DTOs\PaymentOutput;
use App\Services\Payment\Contracts\PaymentGatewayInterface;

class AsaasService implements PaymentGatewayInterface {
    public function charge(int $amountInCents, array $data): PaymentOutput {
        // Regra de Negócio Mockada
        // O Asaas recusa transações acima de 5.000,00 (500000 centavos) neste teste aqui.
        if ($amountInCents > 500000) {
            return new PaymentOutput(
                success: false,
                transactionId: '',
                status: PaymentStatus::DECLINED,
                payload: ['error' => 'limit_exceeded', 'gateway' => 'asaas']
            );
        }

        // Aqui pessoal, vou assumar que PIX e BOLETO nascem pendentes
        return new PaymentOutput(
            success: true,
            transactionId: 'pay_' . bin2hex(random_bytes(10)),
            status: PaymentStatus::PENDING,
            payload: ['gateway' => 'asaas', 'method' => 'pix']
        );
    }
}