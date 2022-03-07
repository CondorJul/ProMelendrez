<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    use HasFactory;

    protected $table = 'prueba';
    protected $fillable = [
        'nombres',
        'apellidos',
        'dni',
        'telefono'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
