<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'pdsId';
    protected $table = 'payment_details';
    protected $fillable = [
        'payId',
        'pdsQuantity',
        'spId',
        /*
        'pdsPeriod' varchar(20),
        'pdsYear' int,*/

        'pdsDescription',

        'pdsUnitPrice',
        'pdsAmount',


    ];
    public function payments()
    {
        return $this->belongsTo(Payment::class, 'payId', 'payId');
    }
}
