<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $primaryKey = 'payId';
    protected $table = 'd_bussines_periods';
    protected $fillable = [
   
        'payState' , /*1=Borrador, 2=Facturado*/
    
        'hqId' ,/*sede*/
        'payKindDoc' ,/*Recibo, Boleta, Factura*/
        'paySerie' , /*Serie */
        'payNumber' , /*Numero correlativo*/
     
        'payDatePrint' ,
        
        'bussId' ,
    
        'tellId' ,
        
        /*CLientes sin regisgro en base de datos*/
        'payClientName',
        'payClientAddress',
        'payClientTel',
        'payClientEmail',
        'payRucOrDni',
        /*Campos para clientes no registrados*/
     
        'paySubTotal',
        'payDiscount' ,
        'paySalesTax' , 
        'payTotal',
        'payTotalInWords',
    
    ];
}
