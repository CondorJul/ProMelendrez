<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\headquater\AddHeadquarterRequest;
use App\Http\Requests\headquater\UpdHeadquarterRequest;
use App\Models\Headquarter;
use Illuminate\Http\Request;

class HeadquarterController extends Controller
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
            'data'=>Headquarter::all()
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddHeadquarterRequest $request)
    {
        $hq = Headquarter::create($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Headquarter::where('hqId', $hq->hqId)->get()
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
    public function update(UpdHeadquarterRequest $request, $id)
    {
        $hq = Headquarter::where('hqId', $request->hqId)->first();
        $hq->hqName = $request->hqName;
        $hq->hqRUC = $request->hqRUC;
        $hq->hqAddress = $request->hqAddress;

        $hq->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => Headquarter::where('hqId', $hq->hqId)->get()
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
        $hq = Headquarter::destroy($id);
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => []
        ], 200);
    }

    public function searchByName($name)
    {
        return Headquarter::where('hqName', $name)->first();
    }
}
