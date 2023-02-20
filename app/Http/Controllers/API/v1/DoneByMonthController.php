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
use Illuminate\Support\Facades\DB;


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

    public function allByDBussPeriod(Request $request){
        $dbms=DoneByMonth::select()
        ->with('dDoneByMonthTasks.task')
        ->where('dbpId', $request->dbpId)
        ->get();

        $tasks = Task::where('tsksState', 1/*1=Activo*/)
        //->where('tsksKindDecl', $request->tsksKindDecl)
        ->get();

        $users=User::select('id', 'perId')->with('person')->get();

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => [
                'dbms'=>$dbms,
                'tasks'=>$tasks,
                'users'=>$users

            ]
        ], 200);
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

        /**1=mensual, 2=Anual */
        $_tsksKindDecl=1;
        if($request->dbmMonth>=13){
            $_tsksKindDecl=2;
        }
        

        $task = Task::where('tsksState', 1/*1=Activo*/)
        ->where('tsksKindDecl',$_tsksKindDecl)
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
        //"Undefined array key "ddbmtCant""

        $user= $request->user();

        $dbm=DoneByMonth::select()
            ->where('dbpId', $request->dbpId)
            ->where('dbmMonth', $request->dbmMonth)
            ->first();
        if(!$dbm){
            $dbm = DoneByMonth::create(array_merge($request->all(),['created_by'=>$user->id]));
            foreach ($request->dDoneByMonthTasks as $key => $value) {
                
                $p=$this->prepareArrayToCreate($value, $dbm, $user);
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

                    if($d->ddbmtState!=5 && $value['ddbmtState']==6){ /*6=pendiente de NO HECHO*/
                        $d->ddbmtState=($value['ddbmtState']==6)?7:$value['ddbmtState'];
                        
                        $d->ddbmtDoneBy=$user->id;
                        $d->ddbmtDoneAt= DB::raw('now()');
                        
                        $d->updated_by=$user->id;
                        $d->save();
                    }
                    if($d->ddbmtState!=5 && $value['ddbmtState']==3){ /*5=es cerrado y no se puede modicar, 3=es disponible para monicar*/

                        $d->ddbmtCant=isset($value['ddbmtCant'])?$value['ddbmtCant']:null;
                        $d->ddbmtAmount=isset($value['ddbmtAmount'])?$value['ddbmtAmount']:null;
                        $d->ddbmtOptionsByComa=isset($value['ddbmtOptionsByComa'])?($value['ddbmtOptionsByComa']):null;
                        $d->ddbmtShortComment=isset($value['ddbmtShortComment'])?$value['ddbmtShortComment']:null;

                        $d->ddbmtIsDoneTask=$value['ddbmtIsDoneTask'];

                        if($value['ddbmtIsDoneTask']){
                            $d->ddbmtDoneBy=$user->id;
                            $d->ddbmtDoneAt= DB::raw('now()');
                        }

                        

                        $d->ddbmtState=($value['ddbmtState']==3)?5:$value['ddbmtState'];

                        /*$d->ddbmtClosedBy=($value['ddbmtState']==4)? $user->id:null;*/
                        $d->updated_by=$user->id;
                        $d->save();
                    }
                    if($d->ddbmtState==5 && $value['ddbmtRectified']==1/*1=pendiente para guardar*/){
                        
                        $d->ddbmtCant=isset($value['ddbmtCant'])?$value['ddbmtCant']:null;
                        $d->ddbmtAmount=isset($value['ddbmtAmount'])?$value['ddbmtAmount']:null;
                        $d->ddbmtOptionsByComa=isset($value['ddbmtOptionsByComa'])?($value['ddbmtOptionsByComa']):null;
                        $d->ddbmtShortComment=isset($value['ddbmtShortComment'])?$value['ddbmtShortComment']:null;

                        $d->ddbmtRectifiedBy=$user->id;
                        $d->ddbmtRectifiedAt = DB::raw('now()');

                        $d->updated_by=$user->id;
                        $d->save();
                    } 

                }else{
                    if($value['ddbmtState']==6){
                        $p=[
                            'dbmId'=>$dbm->dbmId, /*Foreing key */
                            'tsksId'=>$value['tsksId'], /*Foreign key */
    
                            'ddbmtState'=>($value['ddbmtState']==6)?7:$value['ddbmtState'],
                            /*1=Creado por primera vez,  2=pendiente, 3=pendiente para guardar   4=guardado, 5=Cerrado*/
    
                            /*'ddbmtDoneBy'=>($value['ddbmtIsDoneTask'])? $user->id:null,
                            'ddbmtDoneAt'=>($value['ddbmtIsDoneTask'])? DB::raw('now()'):null,*/
                            /*'ddbmtRectifiedBy'=>($value['ddbmtRectifiedBy'])?$user->id:null,*/
    
                            /*'ddbmtClosedBy'=>($value['ddbmtState']==4)? $user->id:null,*/
                            'created_by'=>$user->id
                        ];
                        DDoneByMonthTask::create($p); 
    
                    }else{
                        $p=$this->prepareArrayToCreate($value, $dbm, $user);
                        DDoneByMonthTask::create($p); 
                    }    
                    

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

    public function  prepareArrayToCreate($value, $dbm, $user){
        if($value['ddbmtState']==6){
            $p=[
                'dbmId'=>$dbm->dbmId, /*Foreing key */
                'tsksId'=>$value['tsksId'], /*Foreign key */

                'ddbmtState'=>($value['ddbmtState']==6)?7:$value['ddbmtState'],
                /*1=Creado por primera vez,  2=pendiente, 3=pendiente para guardar   4=guardado, 5=Cerrado*/

                /*'ddbmtDoneBy'=>($value['ddbmtIsDoneTask'])? $user->id:null,
                'ddbmtDoneAt'=>($value['ddbmtIsDoneTask'])? DB::raw('now()'):null,*/
                /*'ddbmtRectifiedBy'=>($value['ddbmtRectifiedBy'])?$user->id:null,*/

                /*'ddbmtClosedBy'=>($value['ddbmtState']==4)? $user->id:null,*/
                'created_by'=>$user->id
            ];
            return $p;
            //DDoneByMonthTask::create($p); 

        }else{
            $p=[
                'dbmId'=>$dbm->dbmId, /*Foreing key */
                'tsksId'=>$value['tsksId'], /*Foreign key */

                'ddbmtCant'=>isset($value['ddbmtCant'])?$value['ddbmtCant']:null,
                'ddbmtAmount'=>isset($value['ddbmtAmount'])?$value['ddbmtAmount']:null,
                'ddbmtOptionsByComa'=> isset($value['ddbmtOptionsByComa'])?($value['ddbmtOptionsByComa']):null,
                'ddbmtShortComment'=>isset($value['ddbmtShortComment'])?$value['ddbmtShortComment']:null,


                'ddbmtIsDoneTask'=>$value['ddbmtIsDoneTask'],
                'ddbmtState'=>($value['ddbmtState']==3)?5:$value['ddbmtState'],//  isset($value['ddbmtState'])?$value['ddbmtState']:null,
                /*1=Creado por primera vez,  2=pendiente, 3=pendiente para guardar   4=guardado, 5=Cerrado*/

                'ddbmtDoneBy'=>($value['ddbmtIsDoneTask'])? $user->id:null,
                'ddbmtDoneAt'=>($value['ddbmtIsDoneTask'])? DB::raw('now()'):null,
                /*'ddbmtRectifiedBy'=>($value['ddbmtRectifiedBy'])?$user->id:null,*/

                /*'ddbmtClosedBy'=>($value['ddbmtState']==4)? $user->id:null,*/
                'created_by'=>$user->id
            ];
            //DDoneByMonthTask::create($p); 
            return $p;
        }    
    }

     public function store(Request $request)
    {
        
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

    public function issetMAA($v = null){
        return isset($v)?$v:null;
    }

   
}
