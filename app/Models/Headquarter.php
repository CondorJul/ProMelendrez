<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Headquarter extends Model
{
    use HasFactory;

    protected $primaryKey = 'hqId';
    protected $table = 'headquarter';
    protected $fillable = [
        'hqId', 
        'hqName',
        'hqRUC',
        'hqAddress',
        'hqTel',
        'hqEmail'
    ];

 
}
