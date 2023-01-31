<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DDoneByMonthTask extends Model
{
    use HasFactory;
    protected $primaryKey = 'ddbmId';
    protected $table = 'd_done_by_month_tasks';
    protected $fillable = [
        "ddbmId" ,
        "tskbmId",  
        "tsksId",
         
        "ddbmShortComment", 
        
        "ddbmIsDoneTask", 
    
    
        "ddbmbDoneBy", 
        "ddbmbClosedBy" , 
    
        "created_by" ,
        "updated_by" ,
        "updated_at" ,
        "created_at" ,
    ];
}
