<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $primaryKey = 'apptmId';
    protected $table = 'appointment';
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
        'apptmTokenToQualify',
        
        'apptmScoreClient',
        'apptmCommentClient',
        'apptmScoreDateClient',
        'apptmCommentDateClient'


    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'apptmId', 'apptmId');
    }
}
