<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\teller\AddTellerRequest;
use App\Http\Requests\teller\UpdTellerRequest;
use Illuminate\Http\Request;
use App\Models\Teller;

class TellerController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function find($id){
        return Teller::find($id);
    }

    public function searchByCode($code){
        return Teller::where('tellCode', $code)->first();
    }

    public function index()
    {
        /*return response()->json([
            'status'=>200,
            'data'=>Teller::all(),
            'message'=>'Obtenido correctamente'
        ], 200);*/

        return Teller::all();
        /*Teller::create(["catCode"=>'34',
    "catName"=>"fjasdfjñlasd"]);*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddTellerRequest $request)
    {
         $teller=Teller::create($request->all());
        return response()->json([
            'res'=>true,
            'msg'=>'Guardado correctamente',
            'data'=>Teller::where('tellId', $teller->catId)->get()
        ],200);
       // return $request;
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
    public function update(UpdTellerRequest $request)
    {
        /*$teller=Teller::where('catId', $request->catId)
            ->update($request->all());*/
        $teller=Teller::where('tellId', $request->tellId)->first();
        $teller->tellCode=$request->tellCode;
        $teller->tellName=$request->tellName;
        
        $teller->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data'=>Teller::where('tellId', $teller->catId)->get()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   $teller=Teller::destroy($id);
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data'=>[]
        ], 200);
    }
}
