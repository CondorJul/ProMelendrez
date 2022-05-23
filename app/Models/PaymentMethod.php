<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $primaryKey = 'paymthdsId';
    protected $table = 'payment_methods';
    protected $fillable = [
        'paymthdsName',
        'paymthdsStatus', /*1=activo, 2=inactivo*/  
    ];
}
