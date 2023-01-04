<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualResumeDetails extends Model
{
    use HasFactory;
    protected $primaryKey = 'ardId';
    protected $table = 'annual_resume_details';
    protected $fillable = [
        'arId', 
        'ardMonth', 
        'ardTaxBase',
        'ardTax' , 
        'ardTotal', 
        'ardPlame', 
        'ardFee', 
    
        'created_by',
        'updated_by',

       
    ];

}
