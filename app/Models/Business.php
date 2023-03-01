<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    public static $snakeAttributes = false;

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
        'bussDetractionsPass',
        'bussSimpleCode',
        'bussSisClave',
        'bussDateMembership',
        'bussDateStartedAct',
        'bussState',
        'bussStateDate',
        'bussFileKind',
        'bussFileNumber',
        'bussRegime',
        'bussKindBookAcc',
        'bussTel',
        'bussTel2',
        'bussTel3',
        'bussEmail',
        'bussObservation',
        'perId',
        'tellId',

        /*Campos añadidos el 16/10/2022 */
        'bussComment',
        'bussCommentColor',
    ];

    
    public function person()
    {
        return $this->belongsTo(Person::class, 'perId', 'perId');
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'd_bussines_periods', 'bussId', 'prdsId')->withPivot(['dbpState', 'dbpId', 'dbpDebt', 'dbpPaid', 'dbpCost'])->as('dbp')->orderBy('prdsNameShort', 'desc');
    }


    public function dBussinesPeriods()
    {
        return $this->hasMany(DBusinessPeriod::class, 'bussId', 'bussId');
        //->orderBy('ppayId', 'asc')
        //->orderBy('svId', 'asc');
        //->orderByDesc(Services::select('svNumberOfOrder')->whereColumn('services.svId', 'services_provided.svId')->first());
        //->orderByRaw('(select "svNumberOfOrder" from services where services."svId"=services_provided."svId") asc');
    }
    public function businessStates(){
        return $this->hasMany(BusinessStates::class, 'bussId', 'bussId'); 
    }

    
}
