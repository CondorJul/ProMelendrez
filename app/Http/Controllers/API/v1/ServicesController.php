<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\md_services\AddServicesRequest;
use App\Http\Requests\md_services\UpdServicesRequest;
use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        return Services::orderBy('svNumberOfOrder', 'asc')->get();
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddServicesRequest $request)
    {
        $service = Services::create($request->all());
        \LogActivity::add($request->user()->email.' ha creado un registro en servicios.', null, json_encode($request->all()));

        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Services::orderBy('svNumberOfOrder', 'asc')->where('svId', $service->svId)->get()
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
    public function update(UpdServicesRequest $request)
    {
        $service = Services::where('svId', $request->svId)->first();

        \LogActivity::add($request->user()->email.' ha actualizado un registro en servicios con id '.$service->svId.'.', json_encode($service), json_encode($request->all()));


        $service->svName = $request->svName;
        $service->svState = $request->svState;
        $service->save();

        return response()->json([
            'res' => true,
            'msg' => 'Servicio Actualizado correctamente',
            'data' => Services::orderBy('svNumberOfOrder', 'asc')->where('svId', $request->svId)->get()
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
        
        $s = Services::whereIn('svId', explode(',', $id),)->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => $s
        ], 200);
    }

    public function stateService($id)
    {
        $service = Services::where('svId', $id)->first();
        $service->svState = $service->svState == 1 ? "2" : "1";
        $service->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado Correctamente.',
        ], 200);
    }

    public function reOrder($ids, Request $request)
    {
        $array=explode(',', $ids);
        for ($i = 0; $i < count($array); ++$i) {
            Services::where('svId', $array[$i])
            ->update([
                'svNumberOfOrder' => $i+1
             ]);
        }
        
        //$s = Services::whereIn('svId', explode(',', $id),)->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Se ha actualizado correctamente.',
            'data' => []
        ], 200);
    }


}
