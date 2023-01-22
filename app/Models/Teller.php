<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

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

    public function categories(){
        
        return $this->belongsToMany(Category::class,'d_category_teller', 'tellId','catId' );
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'tellId', 'tellId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }


    
}
