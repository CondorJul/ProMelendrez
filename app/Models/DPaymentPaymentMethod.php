<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DPaymentPaymentMethod extends Model
{
    use HasFactory;
    public static $snakeAttributes = false;

    protected $primaryKey = 'dppmId';
    protected $table = 'd_payments_payment_methods';
    protected $fillable = [
        "payId",
        "paymthdsId", 
        "dppmAmount",
        "dppmDescription",
    ];

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class, 'paymthdsId', 'paymthdsId');
    } 


}
