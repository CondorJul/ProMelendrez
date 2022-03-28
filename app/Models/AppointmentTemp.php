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
        'tellId'

    ];
}
