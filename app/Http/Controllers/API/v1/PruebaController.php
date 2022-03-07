<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActulizarPruebaRequest;
use App\Http\Requests\GuardarPruebaRequest;
use App\Http\Resources\PruebaResource;
use App\Models\Prueba;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Prueba::all();
        return PruebaResource::collection(Prueba::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuardarPruebaRequest $request)
    {
        /*Prueba::create($request->all());
        return response()->json([
            'res' => true,
            'msg' => "Prueba guardado con exito"
        ], 200);*/
        return (new PruebaResource(Prueba::create($request->all())))->additional(['msg' => "Prueba guardado con exito"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Prueba $prueba)
    {
        /*Hola mundo*/
        /*return response()->json([
            'res' => true,
            'prueba' => $prueba
        ], 200);*/
        return new PruebaResource($prueba);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActulizarPruebaRequest $request, Prueba $prueba)
    {
        $prueba->update($request->all());
        /*return response()->json([
            'res' => true,
            'msg' => 'Prueba actualizado con exito'
        ], 200);*/
        return (new PruebaResource($prueba))->additional(['msg' => "Prueba actualizado con exito"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prueba $prueba)
    {
        $prueba->delete();
        /*return response()->json([
            'res' => true,
            'msg' => 'Prueba eliminado con Exito'
        ], 200);*/
        return (new PruebaResource($prueba))
            ->additional(['msg' => "Prueba eliminado con Exito"])
            ->response()
            ->setStatusCode(202);
    }
}
