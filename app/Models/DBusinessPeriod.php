<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DBusinessPeriod extends Model
{
    use HasFactory;
    protected $primaryKey = 'dbpId';
    protected $table = 'd_bussines_periods';
    protected $fillable = [
        'prdsId',
        'bussId',
        
        'dbpState',
    ];
}
