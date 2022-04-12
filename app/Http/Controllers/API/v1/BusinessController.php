<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\business\AddBusinessWithPersonRequest;
use App\Http\Requests\business\ExistFileNumberRequest;
use App\Http\Requests\business\ExistRucRequest;
use App\Http\Resources\bussinesResource;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Person;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*return bussinesResource::collection(Business::with('person')->get())
            ->additional(['msg' => "lista", 'res' => true])
            ->response()
            ->setStatusCode(200);*/
        return Business::with('person')->get();
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

    public function existRuc(ExistRucRequest $request)
    {
        return $user = Business::where('bussRUC', $request->bussRUC)->first();
    }

    public function existFileNumber(ExistFileNumberRequest $request)
    {
        return $user = Business::where('bussFileNumber', $request->bussFileNumber)->first();
    }

    public function addBusinessWithPerson(AddBusinessWithPersonRequest $request)
    {
        $person = Person::create($request->person);
        $business = new Business();
        $business->bussKind = $request->business['bussKind'];
        $business->bussName = $request->business['bussName'];
        $business->bussRUC = $request->business['bussRUC'];
        $business->bussAddress = $request->business['bussAddress'];
        $business->bussFileKind = $request->business['bussFileKind'];
        $business->bussFileNumber = $request->business['bussFileNumber'];
        $business->bussDateStartedAct = $request->business['bussDateStartedAct'];
        $business->bussDateMembership = $request->business['bussDateMembership'];
        $business->perId = $person->perId;
        $business->save();
        return response()->json([
            'res' => true,
            'msg' => 'Usuario registrado con exito',
            'data' => Business::where('bussId', $business->bussId)->with('person')->get()
        ], 200);
    }
}
