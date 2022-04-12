<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    use HasFactory;
    protected $primaryKey = 'vidId';
    protected $table = 'videos';
    protected $fillable = [
        'vidName',
        'vidLink',
        'vidState'
    ];
}
