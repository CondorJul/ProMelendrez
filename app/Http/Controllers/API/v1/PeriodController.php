<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\period\AddPeriodRequest;
use App\Http\Requests\period\UpdPeriodRequest;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $params=[];
        $queryWhere='';

        if($request->prdsState>0){
            $queryWhere.=' and "prdsState"=?';
            array_push($params,$request->prdsState );
        } 

        $data=Period::select()
            ->whereRaw(' 1=1 '.$queryWhere,[$params])
            ->orderBy('prdsNameShort','DESC')
            ->get();
        
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente.',
            'data'=>$data
        ],200);
       
    
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddPeriodRequest $request)
    {
        $p = Period::create($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Period::where('prdsId', $p->prdsId)->get()
        ], 200);
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
    public function update(UpdPeriodRequest $request, $id)
    {
        $hq = Period::where('prdsId', $id)->first();
        $hq->prdsNameShort = $request->prdsNameShort;
        $hq->prdsState = $request->prdsState;


        $hq->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => Period::where('prdsId', $hq->prdsId)->get()
        ], 200);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $a = Period::whereIn('prdsId', explode(',', $id),)->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => $a
        ], 200);
       
    }
    public function changeState($prdsId, Request $request)
    {
        $q = Period::where('prdsId', $prdsId)->first();
        $q->prdsState = $request->prdsState;
        $q->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado Correctamente.',
        ], 200);
    }
}
