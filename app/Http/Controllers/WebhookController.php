<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Models\PaymentProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handle(Request $request, string $providerSlug)
    {
        Log::info("Webhook recebido de [{$providerSlug}]", $request->all());

        try {
            return DB::transaction(function () use ($request, $providerSlug) {
                
                $provider = PaymentProvider::where('slug', $providerSlug)->firstOrFail();

                $providerTransactionId = $request->input('data.id');
                $externalStatusString = $request->input('status');

                if (!$providerTransactionId || !$externalStatusString) {
                    return response()->json(['message' => 'Dados da requisição inválidos'], 400);
                }

                $transaction = Transaction::where('provider_transaction_id', $providerTransactionId)
                    ->where('payment_provider_id', $provider->id)
                    ->lockForUpdate() 
                    ->first();

                if (!$transaction) {
                    Log::warning("Transação não encontrada: {$providerTransactionId}");
                    return response()->json(['message' => 'Transação não encontrada'], 200);
                }

                if ($transaction->status === PaymentStatus::PAID) {
                    Log::info("Idempotência: Transação {$transaction->id} já processada.");
                    return response()->json(['message' => 'Evento já processado'], 200);
                }

                $newStatus = match ($externalStatusString) {
                    'paid', 'succeeded', 'approved' => PaymentStatus::PAID,
                    'failed', 'declined' => PaymentStatus::FAILED,
                    default => null,
                };

                if (!$newStatus) {
                    return response()->json(['message' => 'Status ignorado.'], 200);
                }

                $transaction->update(['status' => $newStatus]);
                
                Log::info("Transação {$transaction->id} atualizada para {$newStatus->value}");

                return response()->json(['message' => 'Webhook processado']);
            });

        } catch (\Exception $e) {
            Log::error("Erro no webhook: " . $e->getMessage());
            return response()->json(['error' => 'Erro interno.'], 500);
        }
    }
}