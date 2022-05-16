<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentTemp extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'apptmId';
    protected $table = 'appointment_temp';
    protected $fillable = [
        'apptmTicketCode', 
        'apptmDateTimePrint',
        'apptmSendFrom',
        
        'apptKindClient',
        'perId',
        'bussId',
        
        'apptmNumberDocClient',

        'catId',
        'tellId',

        'apptmState',
        'apptmDateStartAttention',
        'apptmNroCalls',

        'apptmTransfer',
        'apptmNameClient',
        'apptmTel',
        'apptmEmail',
        'apptmComment'
    ];

    public function teller(){
        return $this->belongsTo(Teller::class, 'tellId', 'tellId');
    }   

    public function category(){
        return $this->belongsTo(Category::class, 'catId', 'catId');
    }   

}
