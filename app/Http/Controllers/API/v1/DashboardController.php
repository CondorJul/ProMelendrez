<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    
    public function getCounterCards(Request $request){
        $hqId=empty($request->hqId)?0:$request->hqId;

        $array=array();
        $cTellers=DB::select('select  \'Ventanillas\' as title, \'Ventanillas activas\' as "subTitle", \'bg-c-blue\' as class, \'bi bi-tablet-landscape\' as icon , count(*) as total, SUM(case when "tellState"=1 THEN 1 ELSE 0 END) AS "subTotal"   from teller where "hqId"=?', [$hqId]);
        $cCategory=DB::select('select  \'Categorias\' as title, \'Categorias asignadas\' as "subTitle", \'bg-c-green  \' as class, \'tablet\' as icon , count(*) as total, 0 AS "subTotal"   from category where "hqId"=?', [$hqId]);
        $cTickets=DB::select('select  \'Tckets totales\' as title, \'Tickets pendientes\' as "subTitle", \'bg-c-pink  \' as class, \'tablet\' as icon , count(*) as total,  SUM(case when "apptmState"=1 THEN 1 ELSE 0 END) AS "subTotal"   from appointment_temp where "hqId"=?', [$hqId]);
        $cBussines=DB::select('select  \'Clientes totales\' as title, \'Clientes asignados\' as "subTitle", \'bg-c-yellow\' as class, \'tablet\' as icon , count(*) as total,  2 AS "subTotal"   from bussines ');


        array_push($array, $cTellers[0],$cCategory[0], $cTickets[0], $cBussines[0]);
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>$array
        ],200);  
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
