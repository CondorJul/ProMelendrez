<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        if($request->dateStart && $request->dateEnd){
            $queryWhere.='and "created_at" between ? and ? ';
            array_push($params,$request->dateStart, $request->dateEnd);
        }
        else if($request->dateStart){
            $queryWhere.='and "created_at">? ';
            array_push($params,$request->dateStart);
        }
        else if($request->dateEnd){
            $queryWhere.='and "created_at"<? ';
            array_push($params,$request->dateEnd);
        }

        $data=Appointment::select()
            ->whereRaw(' 1=1 '.$queryWhere,[$params])
            //->where('apptmState', 1)
            ->orderBy("created_at", 'DESC')
            ->get();

        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente.',
            'data'=>$data//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=1 '.$queryWhere.' order by "elapsedSeconds" DESC',$params)
        ],200);
    }

    public function getTellers(Request $request){
        $params=[];
        $queryWhere='';

        $queryWhere.='and "hqId" = ?';
        array_push($params, (empty($request->hqId))?0:$request->hqId);
    }

    public function getCategories(Request $request){
        
    }

    public function getApptmState(Request $request){
        
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
