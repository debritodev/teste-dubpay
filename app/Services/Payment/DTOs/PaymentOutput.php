<?php
declare(strict_types=1);
namespace App\Services\Payment\DTOs;
use App\Enums\PaymentStatus;

readonly class PaymentOutput {
    public function __construct(
        public bool $success,
        public string $transactionId,
        public PaymentStatus $status,
        public ?array $payload = []
    ) {}
}