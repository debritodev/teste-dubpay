<?php
declare(strict_types=1);
namespace App\Services\Payment;

use Exception;
use App\Models\User;
use App\Models\Transaction;
use App\Models\PaymentProvider;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected PaymentFactory $factory
    ) {}

    public function process(array $data, ?User $user = null): Transaction
    {
        $providerSlug = $this->factory->resolveSlug($data['provider'] ?? 'auto');
        $providerModel = PaymentProvider::where('slug', $providerSlug)->first();

        if (!$providerModel || !$providerModel->is_active) {
            throw new Exception("O provedor de pagamento [{$providerSlug}] está indisponível.");
        }

        $gateway = $this->factory->make($providerSlug);

        $output = $gateway->charge($data['amount'], $data);

        return DB::transaction(function () use ($user, $providerModel, $data, $output) {
            return Transaction::create([
                'user_id' => $user?->id,
                'payment_provider_id' => $providerModel->id,
                'provider_transaction_id' => $output->transactionId,
                'amount_in_cents' => $data['amount'],
                'status' => $output->status,
                'raw_response' => $output->payload
            ]);
        });
    }
}