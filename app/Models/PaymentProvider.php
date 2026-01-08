<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'base_fee_percentage',
        'is_active',
    ];

    protected $casts = [
        'base_fee_percentage' => 'float',
        'is_active' => 'boolean',
    ];
}