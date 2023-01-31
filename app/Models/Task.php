<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $primaryKey = 'tsksId';
    protected $table = 'tasks';
    protected $fillable = [
        'tsksName', 
        'tsksState', 
        'tsksKindDecl',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];
}
