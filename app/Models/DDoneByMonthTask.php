<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DDoneByMonthTask extends Model
{
    use HasFactory;
    protected $primaryKey = 'ddbmtId';
    protected $table = 'd_done_by_month_tasks';
    protected $fillable = [
        "ddbmtId" ,
        "dbmId",  
        "tsksId",
         
        "ddbmtCant",
        "ddbmtAmount",
        "ddbmtOptionsByComa",
        "ddbmtShortComment", 


        'ddbmtIsDoneTask',
        "ddbmtState",
    
        "ddbmtDoneBy", 
        "ddbmtRectifiedBy",

        "ddbmtRectified",

        "ddbmtDoneAt", 
        "ddbmtRectifiedAt",
        
        /*ddbmtClosedBy" , */
    
        "created_by" ,
        "updated_by" ,
        "updated_at" ,
        "created_at" ,
    ];

    public function task(){
        return $this->belongsTo(Task::class, 'tsksId', 'tsksId');
    }  

}
