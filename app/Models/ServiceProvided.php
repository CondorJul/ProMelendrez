<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProvided extends Model
{
    use HasFactory;

    protected $primaryKey = 'spId';
    protected $table = 'services_provided';
    protected $fillable = [
        'dbpId',
    
        'spStatus', /*1=Borrador, 2=*/
    
        'spTimeInterval',
        'spName',
        'spComment',
        
        'spCost',
        'spCostDate',
    
        'spDebt',
        'spDebtDate',
    
        'spPaid',
        'spPaidDate' ,
    
        'spLimitPaymentDate' ,
    
        'spMaxPartToPay',
    ];
}
