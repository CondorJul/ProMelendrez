<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DPaymentPaymentMethod extends Model
{
    use HasFactory;
    protected $primaryKey = 'dppmId';
    protected $table = 'd_payments_payment_methods';
    protected $fillable = [
        "payId",
        "paymthdsId", 
        "dppmAmount",
        "dppmDescription",
    ];
}
