<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $primaryKey = 'catId';
    protected $table = 'category';
    protected $fillable = [
        'catCode', 'catName',
        'catNameLong', 'catDescription',
        'catAuth', 'catIdParent'
    ];
}
