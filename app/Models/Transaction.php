<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'merchant_request_id',
        'mpesa_receipt_number',
        'phone',
        'amount',
        'status',
    ];
}
