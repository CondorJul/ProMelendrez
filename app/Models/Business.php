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
        'bussAfpUser',
        'bussAfpPass',
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
        'perId',
        'tellId'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'perId', 'perId');
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'd_bussines_periods', 'bussId', 'prdsId')->withPivot(['dbpState', 'dbpId', 'dbpDebt', 'dbpPaid', 'dbpCost'])->as('dbp')->orderBy('prdsNameShort', 'desc');
    }
}
