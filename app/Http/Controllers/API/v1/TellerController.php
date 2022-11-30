<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\teller\AddTellerRequest;
use App\Http\Requests\teller\UpdTellerRequest;
use Illuminate\Http\Request;
use App\Models\Teller;
use Illuminate\Support\Facades\DB;

class TellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function find($id)
    {
        return Teller::find($id);
    }

    public function searchByCode($code)
    {
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

    public function allByHQ(Request $request)
    {
        /*return response()->json([
            'status'=>200,
            'data'=>Teller::all(),
            'message'=>'Obtenido correctamente'
        ], 200);*/

        return Teller::where('hqId', $request->hqId)->get();
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
        $teller = Teller::create($request->all());
        \LogActivity::add($request->user()->email.' ha creado un registro en ventanilla con id '.$teller->tellId.'.', null, json_encode($request->all()));

        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Teller::where('tellId', $teller->tellId)->get()
        ], 200);
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
        $teller = Teller::where('tellId', $request->tellId)->first();
        \LogActivity::add($request->user()->email.' ha actualizado un registro en ventanilla con id '.$teller->tellId.'.', json_encode($teller), json_encode($request->all()));

        $teller->tellCode = $request->tellCode;
        $teller->tellName = $request->tellName;

        $teller->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => Teller::where('tellId', $teller->tellId)->get()
        ], 200);
    }

    public function updState($id, Request $request)
    {
        $teller = Teller::find($id);
        \LogActivity::add($request->user()->email.' ha actualizado el estado en ventanilla con id '.$teller->tellId.'.', json_encode($teller), json_encode($request->all()));

        $teller->tellState = $request->tellState;
        $teller->save();

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => Teller::where('tellId', $teller->tellId)->get()
        ], 200);
    }

    public function updUser($id, Request $request)
    {
        $teller = Teller::find($id);

        \LogActivity::add($request->user()->email.' ha actualizado el usuario asignado en ventanilla con id '.$teller->tellId.'.', json_encode($teller), json_encode($request->all()));

        $teller->userId = $request->userId;
        $teller->save();

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => Teller::where('tellId', $teller->tellId)->get()
        ], 200);
    }

    public function removeUser($id, Request $request)
    {
        $teller = Teller::find($id);
        $teller->userId = null;
        $teller->save();

        return response()->json([
            'res' => true,
            'msg' => 'Usuario removido correctamente',
            'data' => Teller::where('tellId', $teller->tellId)->get()
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
        $teller = Teller::destroy($id);
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => []
        ], 200);
    }


    /*muchos a muchos  */
    public function attachCategory($id, Request $request)
    {
        $teller = Teller::find($id);
        $teller->categories()->attach($request->catId);
        return response()->json([
            'res' => true,
            'msg' => 'Agregado correctamente',
            'data' => Teller::find($id)->categories
        ], 200);
    }

    public function detachCategory($id, $catId)
    {
        $teller = Teller::find($id);
        $teller->categories()->detach($catId);
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente',
            'data' => Teller::find($id)->categories
        ], 200);
    }

    public function getCategories($id)
    {
        return response()->json([
            'res' => true,
            'msg' => 'Leido correctamente',
            'data' => Teller::find($id)->categories
        ], 200);
    }

    //continuar inner join
    public function getJoinPerson(){
        /*DB::raw('select teller.*, person.*  from teller left join users on teller."userId"=users."id" left join person on users.id=person."perId"')*/
        return response()->json([
            'res'=>true,
            'msg'=>'Leido Correctamente',
            'data'=>DB::select('select teller.*, person.*, (select count(*) from appointment_temp where "apptmState"=1 and "tellId"=teller."tellId" ) as "callPending"  from teller left join users on teller."userId"=users."id" left join person on users.id=person."perId"')
        ]);
    }

    public function getJoinPersonByHQ(Request $request){
        /*DB::raw('select teller.*, person.*  from teller left join users on teller."userId"=users."id" left join person on users.id=person."perId"')*/
        return response()->json([
            'res'=>true,
            'msg'=>'Leido Correctamente',
            'data'=>DB::select('select teller.*, person.*, (select count(*) from appointment_temp where "apptmState"=1 and "tellId"=teller."tellId" ) as "callPending"  from teller left join users on teller."userId"=users."id" left join person on users."perId"=person."perId" where teller."hqId"=?',[$request->hqId])
        ]);
    }


    
}
