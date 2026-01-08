<?php
declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;

Route::post('/login', function (Request $request) {
    $user = User::firstOrCreate(
        ['email' => $request->input('email', 'teste@dubpay.com')],
        ['name' => 'Tester', 'password' => Hash::make('password')]
    );

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken
    ]);
});

Route::post('/webhooks/{providerSlug}', [WebhookController::class, 'handle']);


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/payments', [PaymentController::class, 'store']);
});