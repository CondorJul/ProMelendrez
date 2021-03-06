<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $primaryKey = 'perId';
    protected $table = 'person';
    protected $fillable = [
        'perKindDoc',
        'perNumberDoc',
        'perName',
        'perAddress',
        'perTel',
        'perTel2',
        'perTel3',
        'perEmail'
    ];
}
