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
        'svId',
        'spPeriodPayment',
        'ppayId',
        'spName',
        'spCost',
        'spCostDate',
        'spDebt',
        'spDebtDate',
        'spPaid',
        'spPaidDate',
        'spState',
        'spComment',
        'spLimitPaymentDate',
        'spMaxPartToPay',
        'spCommentColourText',
        'created_by',
        'updated_by'
    ];
    public function periodPayments()
    {
        return $this->belongsTo(PeriodPayment::class, 'ppayId', 'ppayId');
    }
    public function services()
    {
        return $this->belongsTo(Services::class, 'svId', 'svId');
    }
    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'spId', 'spId');
    }
}
