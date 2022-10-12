<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\services_provided\AddServicesProvidedRequest;
use App\Http\Requests\services_provided\UpdServicesProvidedRequest;
use App\Models\PaymentDetail;
use App\Models\ServiceProvided;
use Illuminate\Http\Request;

class ServiceProvidedController extends Controller
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
    public function allByDBP(Request $request){
        $r=ServiceProvided::where('dbpId', $request->dbpId)
        ->orderBy('svId','ASC')
        ->orderBy('ppayId','ASC')
        ->get();
        return response()->json([
            'res' => true,
            'msg' => 'Seleccionado correctamente',
            'data' => $r
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddServicesProvidedRequest $request)
    {
        $user=$request->user();
        $sp = ServiceProvided::create(array_merge($request->all(),['created_by'=>$user->id]));

        \App\Helpers\LogActivity::add($request->user()->email.' en control de ejercicio, ha creado el registro con id '.$sp->spId, null, json_encode($request->all()));

        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => ServiceProvided::where('spId', $sp->spId)->get()
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
    public function update(UpdServicesProvidedRequest $request, $spId)
    {
        $user=$request->user();
        $sp=ServiceProvided::where('spId', $spId)->first();

        \App\Helpers\LogActivity::add($request->user()->email.' en control de ejercicio, ha modificado el registro con id '.$sp->spId, json_encode($sp), json_encode($request->all()));
        
        $sp->update(array_merge($request->all(),['updated_by'=>$user->id]));
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => ServiceProvided::where('spId', $sp->spId)->get()
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
        $a = ServiceProvided::whereIn('spId', explode(',', $id))
            ->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => $a
        ], 200);
    }

    public function getPayments($spId){
        $p=ServiceProvided::select()
            ->with('paymentDetails.payment.appointment')
            ->where('spId', $spId)
            ->first();
        return response()->json([
            'res' => true,
            'msg' => 'Seleccionado correctamente.'.$spId,
            'data' => $p
        ], 200);
    }
}
