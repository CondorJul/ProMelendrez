<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoneByMonth extends Model
{
    use HasFactory;
    public static $snakeAttributes = false;


    protected $primaryKey = 'dbmId';
    protected $table = 'done_by_month';
    protected $fillable = [
    
        "dbpId",
        "dbmMonth",
    
        "dbmState",
        "created_by",
        "updated_by",
        "updated_at",
        "created_at"
    ];

    public function dDoneByMonthTasks()
    {
        return $this->hasMany(DDoneByMonthTask::class, 'dbmId', 'dbmId')->orderBy('tsksId', 'asc');
    }

    
}
