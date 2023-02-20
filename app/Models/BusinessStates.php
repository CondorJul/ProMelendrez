<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessStates extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'busssId';
    protected $table = 'business_states';
    protected $fillable = [
        'bussId',
        
        'bussState',
        'bussStateDate',
        'bussComment',

        'busssState',
        
        'created_by',
        'updated_by',
        'updated_at',
        'created_at'
    ];

}
