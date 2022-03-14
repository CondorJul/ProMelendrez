<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    use HasFactory;
    protected $primaryKey = 'tellId';
    protected $table = 'teller';
    protected $fillable = [
        'tellCode', 
        'tellName',
        'tellMaxInWait',
        'tellState',
        'hqId',
        'userId'
    ];
}
