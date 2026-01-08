<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com o usuário (opcional para o teste, mas recomendado)
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('payment_provider_id')->constrained('payment_providers');
            $table->string('provider_transaction_id')->nullable()->index();
            $table->integer('amount_in_cents');
            $table->string('status')->index();
            $table->json('raw_response')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};