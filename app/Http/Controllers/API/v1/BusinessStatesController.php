<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\d_business_period\AddDBusinessPeriodRequest;
use App\Http\Requests\d_business_period\UpdDBusinessPeriodRequest;
use App\Models\BusinessStates;
use App\Models\DBusinessPeriod;
use Illuminate\Http\Request;

class BusinessStatesController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => DBusinessPeriod::all()
        ], 200);
    }


    /*public function statesByBusiness(Request $request){

        BusinessStates::where("bussId",$request->bussId)->orderBy("busssId", "asc")->get();
        
    }*/


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddDBusinessPeriodRequest $request)
    {
        $p = DBusinessPeriod::create($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => DBusinessPeriod::where('dbpId', $p->dbpId)->get()
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
    public function update(UpdDBusinessPeriodRequest $request, $id)
    {
        $q = DBusinessPeriod::where('dbpId', $id)->first();
        $q->prdsId = $request->prdsId;
        $q->bussId = $request->bussId;


        $q->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => DBusinessPeriod::where('dbpId', $q->dbpId)->get()
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
        $q = DBusinessPeriod::destroy($id);
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente',
            'data' => $q
        ], 200);
    }
}
