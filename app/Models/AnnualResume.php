<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AnnualResumeDetails;

class AnnualResume extends Model
{
    use HasFactory;
    public static $snakeAttributes = false;


    protected $primaryKey = 'arId';
    protected $table = 'annual_resume';
    protected $fillable = [
        
        'arDescription', 
        'arState',  /*1=Editable, 2=No editable,*/

        'prdsId', 
        'bussId', 

        'created_by',
        'updated_by',
    ];


    public function annualResumeDetails()
    {
        return $this->hasMany(AnnualResumeDetails::class, 'arId', 'arId');
    }
}
