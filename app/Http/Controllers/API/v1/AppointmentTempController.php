<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\appointment_temp\AddAppointmentTempRequest;
use App\Models\AppointmentTemp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentTempController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>AppointmentTemp::all()
        ],200);
    }

    public function getAllBy(Request $request){
        
        $params=[];
        $queryWhere='';

        $queryWhere.='and "hqId" = ?';
        array_push($params, (empty($request->hqId))?0:$request->hqId);

        if($request->tellId>0){
            $queryWhere.='and "tellId"=?';
            array_push($params,$request->tellId );
        } 
 
        if($request->catId>0){
            $queryWhere.='and "catId"=?';
            array_push($params,$request->catId );
        }

        if($request->apptmState){
            $queryWhere.='and "apptmState"=?';
            array_push($params,$request->apptmState );
            
        }

        $data=AppointmentTemp::select()
            ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention"'))
            ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" '))
            ->with('teller')
            ->with('category')
            ->whereRaw(' 1=1 '.$queryWhere,[$params])
            //->where('apptmState', 1)
            ->orderBy("elapsedSeconds", 'DESC')
            
            ->get();

        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>$data//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=1 '.$queryWhere.' order by "elapsedSeconds" DESC',$params)
        ],200);
    }

    public function startCallByTeller(Request $request){
        
        $a=null;
/*
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>$request
        ],200);*/
        
        if($request->has('apptmId')){           
            $a=AppointmentTemp::where('apptmId', $request->apptmId)
            ->first();
          
        }else if($request->has('tellId')){
            $a=AppointmentTemp::where('tellId', $request->tellId)->where('apptmState',1)
            
            ->first();
        
        }
        $a->update([
            'apptmState'=>2,/*En atención */
            'apptmDateStartAttention'=>DB::raw('now()'),
            'apptmNroCalls'=>DB::raw('"apptmNroCalls"+1')

        ]);

        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>AppointmentTemp::select()
            ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention"'))
            ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" '))
            ->with('teller')
            ->with('category')
            ->where('apptmId', $a->apptmId)
            ->get()//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention", EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmId"=? order by "elapsedSeconds" DESC limit 1',[$a->apptmId])
        ],200);
    }

    public function getAttentionPendingByTeller($tellId){
        $data=AppointmentTemp::select()
        ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention"'))
        ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" '))
        ->with('teller')
        ->with('category')
        ->whereRaw('"apptmState"=2 and "tellId"=?  ',[$tellId])
        //->where('apptmState', 1)
        ->orderBy("elapsedSeconds", 'DESC')
        
        ->get();

        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>$data//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention", EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=2 and "tellId"=? order by "elapsedSeconds" DESC limit 1',[$tellId])
        ],200);
    }

    public function finalizeCall($apptmId, Request $request){
        $a=AppointmentTemp::where('apptmId', $apptmId)
        ->first();
        $a->update([
            'apptmState'=>$request->apptmState,/*Atendido */
            'apptmDateFinishAttention'=>DB::raw('now()'), 
            'apptmComment'=>$request->apptmComment,
            'apptmNameClient'=>$request->apptmNameClient,
            'apptmTel' =>$request->apptmTel,
            'apptmEmail'=>$request->apptmEmail
        ]);

        return response()->json([
            'res'=>true,
            'msg'=>'Atención finalizada correctamente.',
            'data'=>''
        ],200);

    }

    public function callAgain($apptmId){
        $a=AppointmentTemp::where('apptmId', $apptmId)
        ->first();
        
        $a->update([
            'apptmNroCalls'=>DB::raw('"apptmNroCalls"+1')
        ]);

        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention", EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmId"=? order by "elapsedSeconds" DESC limit 1',[$a->apptmId])
        ],200);
    }


    public function undoCall($apptmId){
        $a=AppointmentTemp::where('apptmId', $apptmId)
        ->first();

        $a->update([
            'apptmState'=>1,/*En atención */
        ]);

        return response()->json([
            'res'=>true,
            'msg'=>'Actualizado correctamente',
            'data'=> $a,
            
        ],200);
    }

    public function transferCallToTeller($apptmId, Request $request){
        $a=AppointmentTemp::where('apptmId', $apptmId)
            ->first();
            $a->update([
                'tellId'=>$request->tellId, 
                'apptmState'=>1,/*PENDIENTE*/
                'apptmTransfer'=>DB::raw('"apptmTransfer"+1')
            ]);
        return response()->json([
            'res'=>true,
            'msg'=>'Transferido correctamente.',
            'data'=> $a,
            
        ],200);
    }


    public function getAttentionNoPending(Request $request){
            return response()->json([
            'res'=>true,
            'msg'=>'Leido correctamente.',
            'data'=>AppointmentTemp::select()
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention"'))
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" '))
                ->with('teller')
                ->with('category')
                ->where('apptmState','>', 1)
                ->where('hqId','=', $request->hqId)
                ->orderBy( 'apptmDateStartAttention' ,'DESC' )
                ->limit($request->limit)
                ->get()
            /* DB::select('select *, 
             EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention",
             EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp
             

             where "apptmState">1 order by "apptmDateStartAttention" DESC limit ?',[$request->limit])*/
            
        ],200);
    }

    public function getAll(Request $request){
        return response()->json([
            'res'=>true,
            'msg'=>'Leido correctamente.',
            'data'=>AppointmentTemp::select()
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention"'))
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" '))
                ->with('teller')
                ->with('category')
                ->where('apptmState','>', 1)
                ->orderBy( 'apptmDateStartAttention' ,'DESC' )
                ->limit(1)
                ->get()//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention", EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState">1 order by "apptmDateStartAttention" DESC limit ?',[$request->limit])
            
        ],200);
    }

    public function getNroTotal(Request $request){
      $params=[];
        $queryWhere='';
        if($request->apptmState){
            $queryWhere.='and "apptmState"=?';
            array_push($params,$request->apptmState );
        } 
        if($request->tellId){
            $queryWhere.='and "tellId"=?';
            array_push($params,$request->tellId );
        } 
     

        return response()->json([
            'res'=>true,
            'msg'=>'Leido correctamente.',
            'data'=>DB::select('select count(*) as "nroTotal" from appointment_temp  where 1=1 '.$queryWhere, $params)

        ],200); 
/*
        return response()->json([
            'res'=>true,
            'msg'=>'Leido correctamente.',
            'data'=>User::with('tellers')->get()

        ],200);*/
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddAppointmentTempRequest $request)
    {
        
      
        $a=AppointmentTemp::create($request->all());
        return response()->json([
            'res'=>true,
            'msg'=>'Guardado correctamente',
            'data'=>AppointmentTemp::select()
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention"'))
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" '))
                ->with('teller')
                ->with('category')
                ->where('apptmId', $a->apptmId)
                ->get()
        ],200);
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
    
    public function updateTeller($ids, Request $request){
        $a=AppointmentTemp::whereIn('apptmId', explode(',',$ids),)->update($request->all());
        return response()->json([
            'res'=>true,
            'msg'=>'Actualizado correctamente',
            'data'=> $a,
        ],200);
    }

    public function migrateTickets(Request $request){
        $day='yesterday';  
        if(filter_var($request->migrateToday, FILTER_VALIDATE_BOOLEAN)){
            $day='today';
        }
      

        $a=AppointmentTemp::select()
        ->addSelect(DB::raw("'".$day."'::date as d"))
        ->addSelect(DB::raw("date(created_at)"))
        ->where('hqId',$request->hqId)
            ->where('apptmState','>','2')
            ->where(DB::raw("date(created_at)"),'<=',DB::raw("'".$day."'::date;"))
            ->delete();

            return  response()->json([
                'res'=>true,
                'msg'=>'Se han migrado '.$a.' registros.',
                'data'=>[]
            ],200);;

    }
}
