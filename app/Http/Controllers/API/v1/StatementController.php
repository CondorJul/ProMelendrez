<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DBusinessPeriod;
use App\Models\DoneByMonth;
use App\Models\Period;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    
    
    public function statementsByMonth(Request $request){
        $prdsId=$request->prdsId;
        $dbmMonth=$request->dbmMonth;
        $period=Period::select()->where('prdsId',$prdsId)->first();
        $year=$period->prdsNameShort;

        $dBusinessPeriod=DBusinessPeriod::select()
            ->with([ 'doneByMonths'=>function($query) use($dbmMonth){
                $query->where('dbmMonth',$dbmMonth);
            },
            'doneByMonths.dDoneByMonthTasks'=>function($query3){
                $query3->orderBy('tsksId','asc');
            } ,
            'doneByMonths.dDoneByMonthTasks.task'])
            ->with(['business'])
            ->where('prdsId',$prdsId)
            ->whereHas('doneByMonths')
            ->get();

        /*$businesses=Business::select()
        ->with(['dBussinesPeriods'=>function($query) use($prdsId){
            $query->where('prdsId',$prdsId);
        },
        'dBussinesPeriods.doneByMonth'=>function($query) use($dbmMonth){
            $query->where('dbmMonth',$dbmMonth);

        },
        'dBussinesPeriods.doneByMonth.dDoneByMonthTasks'=>function($query3){
            $query3->orderBy('tsksId','asc');
        } ,
        'dBussinesPeriods.doneByMonth.dDoneByMonthTasks.task'])
        ->with('businessStates') 
        ->get();
        */


        //->whereRaw(' "bussState"=?/*1=Activo*/ or ( extract(MONTH from "bussStateDate")=? and extract(YEAR from "bussStateDate")=?)',["1"/*Activo */, $dbmMonth, $year])
        //->whereRaw('(select "bussStateDate" from business_states where "bussStateDate" > 2023-01-01 and "bussState=1 )')

        /*->whereHas('services.serviceType 
        's', function ($query) use ($id) {
            return $query->where('id', $id);
        })*/
        ///->get();
        /*$businessesReturn=[];
        for($i=0;$i<count($businesses);$i++){
            //evaluamos que tenga period preseleccionado y que tenga registros por mes
            if( count($businesses[$i]->dBussinesPeriods) && count($businesses[$i]->dBussinesPeriods[0]->doneByMonth)){
                array_push($businessesReturn,$businesses[$i]);
            }
            else{
                

            }
        }*/



        return response()->json([
            //'data'=>$businessesReturn,
            //'tasks'=>$tasks 

            'data'=>$dBusinessPeriod
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
