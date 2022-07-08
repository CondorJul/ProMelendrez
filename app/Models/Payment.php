<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $primaryKey = 'payId';
    protected $table = 'payments';
    protected $fillable = [

        'payState', /*1=Borrador, 2=Facturado*/
        'apptmId',
        'hqId',/*sede*/
        'payKindDoc',/*Recibo, Boleta, Factura*/
        'paySerie', /*Serie */
        'payNumber', /*Numero correlativo*/

        'payDatePrint',

        'bussId',

        'tellId',
        'userId',

        /*CLientes sin regisgro en base de datos*/
        'payClientName',
        'payClientAddress',
        'payClientTel',
        'payClientEmail',
        'payClientRucOrDni',
        /*Campos para clientes no registrados*/
        'payTicketSN',
        'payInvoiceSN',
        'payIsCanceled',

        'paySubTotal',
        'payDiscount',
        'paySalesTax',
        'payTotal',
        'payTotalInWords',
        'created_by',
        'updated_by',
        'payReceiptHonorarySN'

    ];
    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'payId', 'payId');
    }
    public function dPaymentPaymentMethods()
    {
        return $this->hasMany(DPaymentPaymentMethod::class, 'payId', 'payId');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
