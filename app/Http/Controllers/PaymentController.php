<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Payment\PaymentService;
use App\Http\Requests\StorePaymentRequest;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, PaymentService $service): JsonResponse
    {
        try {
            $transaction = $service->process(
                $request->validated(), 
                $request->user()
            );

            $isSuccess = $transaction->status !== 'DECLINED' && $transaction->status !== 'FAILED';

            return response()->json([
                'message' => $isSuccess ? 'Pagamento processado com sucesso.' : 'Pagamento recusado.',
                'data' => $transaction
            ], $isSuccess ? 201 : 422);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao processar pagamento.',
                'details' => $e->getMessage()
            ], 400);
        }
    }
}