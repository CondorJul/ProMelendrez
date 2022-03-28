<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $primaryKey = 'bussId';
    protected $table = 'bussines';
    protected $fillable = [
        'bussKind',
        'bussName',
        'bussRUC',
        'bussAdress',
        'bussSunatUser',
        'bussSunatPass',
        'bussCodeSend',
        'bussCodeRNP',
        'bussDateMembership',
        'bussDateStartedAct',
        'bussState',
        'bussStateDate',
        'bussFileKind',
        'bussFileNumber',
        'bussRegime',
        'bussKindBookAcc',
        'bussTel',
        'bussEmail',
        'bussObservation',
        'perId'
    ];
}
