<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DBusinessPeriod;
use App\Models\DoneByMonth;
use App\Models\Period;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StatementController extends Controller
{
    
    
    public function statementsByMonth(Request $request){
        $prdsId=$request->prdsId;
        $dbmMonth=$request->dbmMonth;
        $ln=$request->ln;
        $period=Period::select()->where('prdsId',$prdsId)->first();
        $year=$period->prdsNameShort;

        $dBusinessPeriod=DBusinessPeriod::selectRaw('d_bussines_periods.*, bussines."bussRUC", bussines."bussName",  RIGHT("bussRUC",1) as "_lastDigit"')
            ->join('bussines', 'bussines.bussId', '=', 'd_bussines_periods.bussId')
            ->with([ 'doneByMonths'=>function($query) use($dbmMonth){
                $query->where('dbmMonth',$dbmMonth);
            },
            'doneByMonths.dDoneByMonthTasks'=>function($query3){
                $query3->orderBy('tsksId','asc');
            } ,
            'doneByMonths.dDoneByMonthTasks.task'])
            /*->with(['business'=>function($query){
                $query->orderByRaw(' RIGHT("bussRUC",1) ASC, "bussName" asc ');
            }])*/
            ->with('business')
            ->where('prdsId',$prdsId)
            ->whereRaw('(RIGHT("bussRUC",1)=? or -1=?) ', [$ln, $ln])
            //->has('doneByMonths')
            ->whereHas('doneByMonths', function($query) use ($dbmMonth){
                $query->where('dbmMonth',$dbmMonth);
            })
            /*->whereHas('business', function($query) use ($ln){
                $query->whereRaw(' (RIGHT("bussRUC",1)=? or -1=?) ',[$ln,$ln]);
            })*/
            ->orderByRaw('RIGHT("bussRUC",1) ASC, "bussName" asc ')
            ->get();

        $users=User::select('id', 'perId')->with('person')->get();


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
            
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => [
                'dBusinessesPeriod'=>$dBusinessPeriod,
                'users'=>$users

            ]
            //'data'=>$businessesReturn,
            //'tasks'=>$tasks    
        ]);
    }



    public function summary(Request $request){

        $period = Period::select()->where('prdsId', $request->prdsId)->first();
        $year=$period->prdsNameShort;
        $month=$request->dbmMonth;
        $totalMonths=$year*12+$month;
        $bussState='1';


        $params=[];
        $arrayBusinessess = DB::select('
            SELECT count(*) AS total, RIGHT("bussRUC", 1) AS "_lastDigit"  FROM bussines  
            where 1=1 AND 
                (
                    ( 
                        "bussState"=?/*Activo*/  
                        and (extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate")<=?)
                    )
                    OR 
                    EXISTS 
                    (
                        select * from business_states 
                            where business_states."bussId" = bussines."bussId" and business_states."bussState"=? 
                            and
                            (
                                extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate"))<=?
                                and ?<=(extract(YEAR from "bussStateDateNew")*12+extract(MONTH from "bussStateDateNew")
                            )
                    )
                )
                
                GROUP BY RIGHT("bussRUC", 1); 
                ', 
                [$bussState,  $totalMonths,$bussState, $totalMonths, $totalMonths]
            );


            $arrayStatements = DB::select('
                SELECT count(*) AS total, RIGHT(b."bussRUC", 1) AS "_lastDigit" FROM bussines b 
                INNER JOIN d_bussines_periods dbp on b."bussId"=dbp."bussId"
                INNER JOIN done_by_month dbm on dbp."dbpId"=dbm."dbpId" where dbp."prdsId"=? and dbm."dbmMonth"=? GROUP BY RIGHT(b."bussRUC", 1);
                ', 
                [$request->prdsId,  $month]
             );
             $arrayP=[];
             for($i=0;$i<10;$i++){
                $t=array();
                $t["digit"]=$i;

                $b = array_filter($arrayBusinessess, function ($row) use($i) {
                    return intval($row->_lastDigit)==intval($i);
                });
                $b=array_values($b);
                $t["business"]=($b)?$b[0]->total:0;
                
                $s = array_filter($arrayStatements, function ($row) use($i) {
                    return intval($row->_lastDigit)==intval($i);
                });
                $s=array_values($s);
                $t["statements"]=($s)?$s[0]->total:0;

                if($t["statements"]==0){
                    $t["message"]='Sin declaraciones';
                    $t["state"]='success';
                    $t["badge"]='badge badge-light';

                }elseif($t["statements"]<$t["business"]){
                    $t["message"]='Incompleto';
                    $t["state"]='warner';
                    $t["badge"]='badge badge-warning';

                    
                }elseif($t["business"]==$t["statements"]){
                    $t["message"]='Completo';
                    $t["state"]='success';
                    $t["badge"]='badge badge-success';

                }elseif($t["statements"]>$t["business"]){
                    $t["message"]='Atención';
                    $t["state"]='success';
                    $t["badge"]='badge badge-danger';

                    
                }

                array_push($arrayP, $t);
             }

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => [
              'businessess'=>$arrayBusinessess,
              'statements'=>$arrayStatements,
              'arrayP'=>$arrayP
            ]
        ]);
    }


    public function pendings(Request $request){

        $period = Period::select()->where('prdsId', $request->prdsId)->first();
        $year=$period->prdsNameShort;
        $month=$request->dbmMonth;
        $totalMonths=$year*12+$month;
        $bussState='1';


        $params=[];
        $arrayBusinessess = DB::select('
            SELECT *, RIGHT("bussRUC", 1) AS "_lastDigit"  FROM bussines  
            where 1=1 AND 
                (
                    ( 
                        "bussState"=?/*Activo*/  
                        and (extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate")<=?)
                    )
                    OR 
                    EXISTS 
                    (
                        select * from business_states 
                            where business_states."bussId" = bussines."bussId" and business_states."bussState"=? 
                            and
                            (
                                extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate"))<=?
                                and ?<=(extract(YEAR from "bussStateDateNew")*12+extract(MONTH from "bussStateDateNew")
                            )
                    )
                )
                
                ; 
                ', 
                [$bussState,  $totalMonths,$bussState, $totalMonths, $totalMonths]
            );


            $arrayStatements = DB::select('
                SELECT b.*, RIGHT(b."bussRUC", 1) AS "_lastDigit" FROM bussines b 
                INNER JOIN d_bussines_periods dbp on b."bussId"=dbp."bussId"
                INNER JOIN done_by_month dbm on dbp."dbpId"=dbm."dbpId" where dbp."prdsId"=? and dbm."dbmMonth"=?;
                ', 
                [$request->prdsId,  $month]
             );

             $arrayP=[];
             $arrayO=[];
             for($i=0;$i<count($arrayStatements);$i++){
                $_bussId=$arrayStatements[$i]->bussId;

                $key=array_search($_bussId, array_column($arrayBusinessess, true), 'bussId');
                array_push($arrayO, $key);
                if($key){

                    array_push($arrayP, $arrayStatements[$i]);
                    unset($arrayStatements[$i]);
                    
                    unset($arrayBusinessess[$key]);
                    
                }





                /*$_bussId=$arrayStatements[$i]->bussId;

                $b = array_filter($arrayBusinessess, function ($row) use($_bussId) {
                    return $row->bussId==$_bussId;
                });

                $b=array_values($b);
                if($b){
                    array_values($arrayP[],  )
                }else{

                }
                */

             }





             /*
             for($i=0;$i<10;$i++){
                $t=array();
                $t["digit"]=$i;

                $b = array_filter($arrayBusinessess, function ($row) use($i) {
                    return intval($row->_lastDigit)==intval($i);
                });
                $b=array_values($b);
                $t["business"]=($b)?$b[0]->total:0;
                
                $s = array_filter($arrayStatements, function ($row) use($i) {
                    return intval($row->_lastDigit)==intval($i);
                });
                $s=array_values($s);
                $t["statements"]=($s)?$s[0]->total:0;

                if($t["statements"]==0){
                    $t["message"]='Sin declaraciones';
                    $t["state"]='success';
                    $t["badge"]='badge badge-light';

                }elseif($t["statements"]<$t["business"]){
                    $t["message"]='Incompleto';
                    $t["state"]='warner';
                    $t["badge"]='badge badge-warning';

                    
                }elseif($t["business"]==$t["statements"]){
                    $t["message"]='Completo';
                    $t["state"]='success';
                    $t["badge"]='badge badge-success';

                }elseif($t["statements"]>$t["business"]){
                    $t["message"]='Atención';
                    $t["state"]='success';
                    $t["badge"]='badge badge-danger';

                    
                }

                array_push($arrayP, $t);
             }*/

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => [
              'businessess'=>$arrayBusinessess,
              'statements'=>$arrayStatements,
              'arrayP'=>$arrayP,
              'arrayO'=>$arrayO
            ]
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
