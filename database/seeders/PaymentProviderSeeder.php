<?php
declare(strict_types=1);
namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    public function run(): void
    {
        PaymentProvider::firstOrCreate([
            'slug' => 'stripe'
        ], [
            'name' => 'Stripe Payments',
            'is_active' => true,
            'base_fee_percentage' => 2.99
        ]);

        PaymentProvider::firstOrCreate([
            'slug' => 'asaas'
        ], [
            'name' => 'Asaas',
            'is_active' => true,
            'base_fee_percentage' => 1.99
        ]);
    }
}