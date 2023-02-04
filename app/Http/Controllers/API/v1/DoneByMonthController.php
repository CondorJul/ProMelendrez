<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DBusinessPeriod;
use App\Models\DDoneByMonthTask;
use App\Models\DoneByMonth;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DoneByMonthController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function findByBusiness(Request $request )
    {
        $b=Business::select()
            ->with('person')
            ->where('bussId', $request->bussId)
            ->first();
        $dbp=DBusinessPeriod::where('bussId', $request->bussId)
            ->where('prdsId', $request->prdsId)
            ->first();

        $dbm=DoneByMonth::select()
            ->with('dDoneByMonthTasks.task')
            ->where('dbpId', $dbp->dbpId)
            ->where('dbmMonth', $request->dbmMonth)
            ->first();

        

        $task = Task::where('tsksState', 1/*1=Activo*/)
        ->where('tsksKindDecl', $request->tsksKindDecl)
        ->get();

        $users=User::select('id', 'perId')->with('person')->get();


        /*Preparar un modelo vacio */
        
        /*if(!$dbm){
            
            $dbmModel=[
                'dbpId'=>$dbp->dbpId,
                'dbmMonth'=>$request->dbmMonth
            ]
            ;    
        }*/

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => [
                'business'=>$b, 
                'dbp'=>$dbp, 
                'dbm'=>$dbm,
                'task'=>$task,
                'users'=>$users

            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public  function addUpd(Request $request)
    {
        $user= $request->user();

        $dbm=DoneByMonth::select()
            ->where('dbpId', $request->dbpId)
            ->where('dbmMonth', $request->dbmMonth)
            ->first();
        if(!$dbm){
            $dbm = DoneByMonth::create(array_merge($request->all(),['created_by'=>$user->id]));
            foreach ($request->dDoneByMonthTasks as $key => $value) {
                    $p=[
                        'dbmId'=>$dbm->dbmId, /*Foreing key */
                        'tsksId'=>$value['tsksId'], /*Foreign key */

                        'ddbmtShortComment'=>isset($value['ddbmtShortComment'])?$value['ddbmtShortComment']:null,
                        'ddbmtIsDoneTask'=>$value['ddbmtIsDoneTask'],
                        'ddbmtState'=>isset($value['ddbmtState'])?$value['ddbmtState']:null,

                        'ddbmtDoneBy'=>($value['ddbmtIsDoneTask'])? $user->id:null,

                        'ddbmtClosedBy'=>($value['ddbmtState']==4)? $user->id:null,
                        'created_by'=>$user->id
                    ];
                    DDoneByMonthTask::create($p); 
            }

        }else{

            //$dbm = DoneByMonth::create(array_merge($request->all(),['created_by'=>$user->id]));

            foreach ($request->dDoneByMonthTasks as $key => $value) {
                $d=DDoneByMonthTask::where([
                    'dbmId'=>$dbm->dbmId , 
                    'tsksId'=>$value['tsksId']
                ])->first();
                
                if($d){

                    if($d->ddbmtState!=4){ /*4 es cerrado y no se puede modificar */

                        $d->ddbmtShortComment=isset($value['ddbmtShortComment'])?$value['ddbmtShortComment']:null;
                        $d->ddbmtState=isset($value['ddbmtState'])?$value['ddbmtState']:null;

                        if($d->ddbmtIsDoneTask<>$value['ddbmtIsDoneTask']){
                            $d->ddbmtDoneBy=$user->id;
                        }
                        $d->ddbmtIsDoneTask=isset($value['ddbmtIsDoneTask'])?$value['ddbmtIsDoneTask']:null;
                        $d->ddbmtClosedBy=($value['ddbmtState']==4)? $user->id:null;
                        $d->updated_by=$user->id;
                        $d->save();
                    } 

                }else{
                    $p=[
                        'dbmId'=>$dbm->dbmId, /*Foreing key */
                        'tsksId'=>$value['tsksId'], /*Foreign key */

                        'ddbmtShortComment'=>isset($value['ddbmtShortComment'])?$value['ddbmtShortComment']:null,
                        'ddbmtIsDoneTask'=>$value['ddbmtIsDoneTask'],
                        'ddbmtState'=>isset($value['ddbmtState'])?$value['ddbmtState']:null,

                        'ddbmtDoneBy'=>($value['ddbmtIsDoneTask'])? $user->id:null,

                        'ddbmtClosedBy'=>($value['ddbmtState']==4)? $user->id:null,
                        'created_by'=>$user->id
                    ];
                    DDoneByMonthTask::create($p); 

                }
                
            }

    
            //$p->annualResumeDetails()->createMany($request->annualResumeDetails);
           // \LogActivity::add($request->user()->email.' realizó el registro de resumen anual '.$p->arId, null, json_encode($request->all()));

        }
        $dbm=DoneByMonth::select()
        ->with('dDoneByMonthTasks.task')
        ->where('dbpId', $request->dbpId)
        ->where('dbmMonth', $request->dbmMonth)
        ->first();


        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => $dbm
        ], 200);
    }

     public function store(Request $request)
    {
        
    }

//    "SQLSTATE[42703]: Undefined column: 7 ERROR:  no existe la columna «ddbmId»\nLINE 1: ...($1, $2, $3, $4, $5, $6, $7, $8, $9, $10) returning \"ddbmId\"\n 

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
