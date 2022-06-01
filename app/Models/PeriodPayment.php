<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodPayment extends Model
{
    use HasFactory;
    protected $primaryKey = 'ppayId';
    protected $table = 'period_payments';
    protected $fillable = [
   
        'ppayName',
	    'ppayState'
    
    ];


}
