<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $primaryKey = 'adtId';
    protected $table = 'audits';
    protected $fillable = [
        'adtUserAgent',
        'adtMethod',
        'adtURL',
        'adtIP',
    
        'adtNameTable',
        
        'adtKeyWord', 
        'adtSubject',
    
        'adtDataOld' ,
        'adtDataNew' ,
    
        'adtSystem',
        'adtHostname',
        
        'created_by' ,
        'updated_by'
    ];

    
}
