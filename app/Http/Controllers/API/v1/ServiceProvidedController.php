<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\services_provided\AddServicesProvidedRequest;
use App\Http\Requests\services_provided\UpdServicesProvidedRequest;
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
        $r=ServiceProvided::where('dbpId', $request->dbpId)->get();
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
        $sp = ServiceProvided::create($request->all());
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
        $sp=ServiceProvided::where('spId', $spId)->first();

        $sp->update($request->all());
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
        //
    }
}
