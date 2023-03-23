<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DBusinessPeriod extends Model
{
    use HasFactory;
    public static $snakeAttributes = false;

    protected $primaryKey = 'dbpId';
    protected $table = 'd_bussines_periods';
    protected $fillable = [
        'prdsId',
        'bussId',
        'dbpState',
    ];
    public function serviceProvided()
    {
        return $this->hasMany(ServiceProvided::class, 'dbpId', 'dbpId')
        ->orderBy('ppayId', 'asc')
        //->orderBy('svId', 'asc');
        //->orderByDesc(Services::select('svNumberOfOrder')->whereColumn('services.svId', 'services_provided.svId')->first());
        ->orderByRaw('(select "svNumberOfOrder" from services where services."svId"=services_provided."svId") asc');
    }
    public function periods()
    {
        return $this->belongsTo(Period::class, 'prdsId', 'prdsId');
    }

    public function doneByMonths()
    {
        return $this->hasMany(DoneByMonth::class, 'dbpId', 'dbpId')
            ->orderBy('dbmMonth', 'asc')
        ;
        //->orderBy('ppayId', 'asc')
        //->orderBy('svId', 'asc');
        //->orderByDesc(Services::select('svNumberOfOrder')->whereColumn('services.svId', 'services_provided.svId')->first());
        //->orderByRaw('(select "svNumberOfOrder" from services where services."svId"=services_provided."svId") asc');
    }
    public function business()
    {
        return $this->belongsTo(Business::class, 'bussId', 'bussId');
    }
}

/*
$items = UserItems
        ::where('user_id','=',$this->id)
        ->where('quantity','>',0)
        ->join('items', 'items.id', '=', 'user_items.item_id')
        ->orderBy('items.type')
        ->select('user_items.*') //see PS:
        ->get();*/