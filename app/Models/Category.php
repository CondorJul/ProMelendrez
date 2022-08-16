<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teller;

class Category extends Model
{
    use HasFactory;
    protected $primaryKey = 'catId';
    protected $table = 'category';
    protected $fillable = [
        'catCode',
        'catName',
        'catLinkBus',
        'catNameLong',
        'catDescription',
        'catAuth',
        'catIdParent',
        'hqId',
        'catState'
    ];

    public function tellers(){
        return $this->belongsToMany(Teller::class,'d_category_teller','catId', 'tellId' );
    }
}
