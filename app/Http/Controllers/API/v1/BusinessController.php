<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\business\AddBusinessWithPersonRequest;
use App\Http\Requests\business\ExistFileNumberRequest;
use App\Http\Requests\business\ExistRucRequest;
use App\Http\Requests\business\updAdiDataRequest;
use App\Http\Requests\business\updAfiDataRequest;
use App\Http\Requests\business\updBussDataRequest;
use App\Http\Requests\business\updPerDataRequest;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

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
    public function allSummarized()
    {
        /*return bussinesResource::collection(Business::with('person')->get())
            ->additional(['msg' => "lista", 'res' => true])
            ->response()
            ->setStatusCode(200);*/
        return Business::select('bussId', 'bussRUC', 'bussName', 'bussFileNumber')->get();
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

    /*Añadido por Ricardo */
    /* public function search(Request $request){
        $l='%';
        if($request->like){
            $l='%'.$request->like.'%';
        }

        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>Business::whereRaw(' "bussRUC" like ? or "bussName" like ? or "bussEmail" like ? ' ,[$l,$l,$l])
                ->limit(50)
                ->get()
        ],200);
        }*/

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
        $business->tellId = $request->business['tellId'];
        $business->perId = $person->perId;
        $business->save();
        return response()->json([
            'res' => true,
            'msg' => 'Usuario registrado con exito',
            'data' => Business::where('bussId', $business->bussId)->with('person')->get()
        ], 200);
    }

    public function viewBusinessPerson($id)
    {
        //Business::with('person')->get();
        return Business::with('person')->where('bussId', $id)->get();
    }

    public function updateBusinessData(updBussDataRequest $request)
    {
        $bussData = Business::where('bussId', $request->bussId)->first();
        $bussData->bussKind = $request->business['bussKind'];
        $bussData->bussName = $request->business['bussName'];
        $bussData->bussRUC = $request->business['bussRUC'];
        $bussData->bussAddress = $request->business['bussAddress'];
        $bussData->bussFileKind = $request->business['bussFileKind'];
        $bussData->bussFileNumber = $request->business['bussFileNumber'];
        $bussData->bussState = $request->business['bussState'];
        $bussData->bussTel = $request->business['bussTel'];
        $bussData->bussTel2 = $request->business['bussTel2'];
        $bussData->bussTel3 = $request->business['bussTel3'];
        $bussData->save();
        return response()->json([
            'res' => true,
            'msg' => 'Datos de Negocio Actualizado',
            'data' => Business::where('bussId', $request->bussId)->get()
        ], 200);
    }

    public function updatePersonData(updPerDataRequest $request)
    {
        $perData = Person::where('perId', $request->person['perId'])->first();
        $perData->perKindDoc = $request->person['perKindDoc'];
        $perData->perName = $request->person['perName'];
        $perData->perNumberDoc = $request->person['perNumberDoc'];
        $perData->perAddress = $request->person['perAddress'];
        $perData->perEmail = $request->person['perEmail'];
        $perData->perTel = $request->person['perTel'];
        $perData->perTel2 = $request->person['perTel2'];
        $perData->perTel3 = $request->person['perTel3'];
        $perData->save();
        return response()->json([
            'res' => true,
            'msg' => 'Datos de Persona Actualizado',
            'data' => Person::where('perId', $request->person['perId'])->get()
        ], 200);
    }

    public function updateAfiliationData(updAfiDataRequest $request)
    {
        $afiData = Business::where('bussId', $request->bussId)->first();
        $afiData->bussSunatUser = $request->afiliation['bussSunatUser'];
        $afiData->bussSunatPass = $request->afiliation['bussSunatPass'];
        $afiData->bussCodeSend = $request->afiliation['bussCodeSend'];
        $afiData->bussCodeRNP = $request->afiliation['bussCodeRNP'];
        $afiData->bussAfpUser = $request->afiliation['bussAfpUser'];
        $afiData->bussAfpPass = $request->afiliation['bussAfpPass'];
        $afiData->bussSimpleCode = $request->afiliation['bussSimpleCode'];
        $afiData->bussDetractionsPass = $request->afiliation['bussDetractionsPass'];
        $afiData->bussSisClave = $request->afiliation['bussSisClave'];
        $afiData->save();
        return response()->json([
            'res' => true,
            'msg' => 'Datos de Afiliacion Actualizado',
            'data' => Business::where('bussId', $request->bussId)->get()
        ], 200);
    }

    public function updateAditionalData(updAdiDataRequest $request)
    {
        $adiData = Business::where('bussId', $request->bussId)->first();
        $adiData->bussDateMembership = $request->aditional['bussDateMembership'];
        $adiData->bussDateStartedAct = $request->aditional['bussDateStartedAct'];
        $adiData->bussRegime = $request->aditional['bussRegime'];
        $adiData->bussKindBookAcc = $request->aditional['bussKindBookAcc'];
        $adiData->bussEmail = $request->aditional['bussEmail'];
        $adiData->bussObservation = $request->aditional['bussObservation'];
        $adiData->save();
        return response()->json([
            'res' => true,
            'msg' => 'Datos Adicionales Actualizado',
            'data' => Business::where('bussId', $request->bussId)->get()
        ], 200);
    }

    /*Periods*/
    public function addPeriod($bussId, Request $request)
    {
        $q = Business::find($bussId);
        $q->periods()->attach($request->prdsId, $request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Agregado correctamente',
            'data' => Business::find($bussId)->periods
        ], 200);
    }

    public function delPeriod($bussId, $prdsId)
    {
        $q = Business::find($bussId);
        $q->periods()->detach($prdsId);
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente',
            'data' => Business::find($bussId)->periods
        ], 200);
    }

    public function allPeriods($bussId)
    {
        return response()->json([
            'res' => true,
            'msg' => 'Leido correctamente',
            'data' => Business::find($bussId)->periods
        ], 200);
    }

    public function getTellerJoinUsers(Request $request)
    {
        return response()->json([
            'res' => true,
            'msg' => 'Leido Correctamente',
            'data' => DB::select('SELECT "tellId", "tellCode", "tellName", users."name", teller."hqId", (SELECT COUNT(*) FROM bussines WHERE bussines."tellId"=teller."tellId") AS "cantBusiness" FROM teller LEFT JOIN users ON teller."userId"=users."id" WHERE teller."hqId"=?', [$request->hqId])
        ]);
    }
}
