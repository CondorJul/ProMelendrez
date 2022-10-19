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

    public function allFileNumbers()
    {
        /*return bussinesResource::collection(Business::with('person')->get())
            ->additional(['msg' => "lista", 'res' => true])
            ->response()
            ->setStatusCode(200);*/
        return Business::select('bussId','bussState', 'bussFileNumber')->orderBy('bussFileNumber', 'ASC')->get();
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
    public function destroy(Request $request, $bussId)
    {
        $p=Business::find($bussId)->periods;
        $b = Business::where('bussId', $bussId)->first();
        $c=0;
        $msg='';
        $res=false;
        if(count($p)==0){
            \LogActivity::add($request->user()->email.' ha eliminado el cliente con id '.$bussId.', RUC='.$b->bussRUC.', NAME='.$b->bussName,null, json_encode($request->all()));
            $c=$b->delete();
            $res=true;
            $msg='Se ha eliminado '.$c.' registro(s).';
        } else {
            \LogActivity::add($request->user()->email.' ha intentado eliminar el cliente con id '.$bussId.', RUC='.$b->bussRUC.', NAME='.$b->bussName,null, json_encode($request->all()));
            $msg='Al parecer este cliente cuenta con información y no es posible eliminar.';
            $res=false;

        }

        


        return response()->json([
            'res' => $res,
            'msg' => $msg,
            'data' => $c
        ], 200);
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
        
        \LogActivity::add($request->user()->email.' ha creado un cliente con id '.$business->bussId.', RUC='.$business->bussRUC.', NAME='.$business->bussName,null, json_encode($request->all()));

        return response()->json([
            'res' => true,
            'msg' => 'Usuario registrado con exito',
            'data' => Business::where('bussId', $business->bussId)->with('person')->get()
        ], 200);
    }

    public function viewBusinessPerson(Request $request, $bussId )
    {
        //Business::with('person')->get();
        $b=Business::with('person')->where('bussId', $bussId)->get();
        \LogActivity::add($request->user()->email.' ha realizado una consulta de cliente con id '.$bussId.', RUC='.$b[0]->bussRUC.', NAME='.$b[0]->bussName,null, json_encode($request->all()));
        
        return $b; 
    }

    public function updateBusinessData(updBussDataRequest $request)
    {
        $bussData = Business::where('bussId', $request->bussId)->first();

        \LogActivity::add($request->user()->email.' ha actualizado los datos de negocio de cliente con id '.$bussData->bussId,json_encode($bussData), json_encode($request->all()));

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
        
        \LogActivity::add($request->user()->email.' ha actualizado los datos de persona de cliente con id '.$perData->bussId,json_encode($perData), json_encode($request->all()));


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

        \LogActivity::add($request->user()->email.' ha actualizado los datos de afiliacion de cliente con id '.$afiData->bussId,json_encode($afiData), json_encode($request->all()));

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

        \LogActivity::add($request->user()->email.' ha actualizado los datos adicionales de cliente con id '.$adiData->bussId,json_encode($adiData), json_encode($request->all()));


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
            'data' => DB::select('SELECT "tellId", "tellCode", "tellName", users."name", teller."hqId", (SELECT COUNT(*) FROM bussines WHERE bussines."tellId"=teller."tellId") AS "cantBusiness" FROM teller LEFT JOIN users ON teller."userId"=users."id" WHERE teller."hqId"=?  ORDER BY "tellCode" ASC', [$request->hqId])
        ]);
    }

    public function getBusinessOfTeller(Request $request)
    {
        /*Get*/

        $params=[];
        $queryWhere='';


        if($request->tellId>0){
            $queryWhere.=' and "tellId"=?';
            array_push($params,$request->tellId );
        }
        
        if($request->bussState>0){
            $queryWhere.=' and "bussState"=?';
            array_push($params,$request->bussState);
        }

        if(!empty($request->q)){
            $queryWhere.=' and (lower("bussName" ) like lower(?) or "bussRUC" like ? or "bussFileNumber"::text = ? )';
            $q='%'.$request->q.'%';
            array_push($params,$q, $q, $request->q);
        }

        /*if ($request->tellId == 0) {
            $data = Business::with('person')->get();
        } else {
            $data = Business::with('person')->where('tellId', $request->tellId)->get();
        }*/
        $h='%'.$request->q.'%';

        $data=Business::select()
        ->with('person')
  
        //->with('person')
        ->whereRaw(' 1=1 '.$queryWhere,[$params])
        /*->whereHas('person', function($q) use($h) {
            $q->whereRaw(' lower("perName" ) like lower(?) ',[$h]);
        })*/
        ->orderBy("bussName", 'ASC')
        ->get();

        return response()->json([
            'res' => true,
            'msg' => 'Leido Correctamente',
            'data' => $data
        ]);
    }

    public function updateBusinessTeller($ids, Request $request)
    {
        $a = Business::whereIn('bussId', explode(',', $ids),)->update($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => $a,
        ], 200);
    }

    public function updateBusinessState($ids, Request $request)
    {
        $b= Business::select()->whereIn('bussId', explode(',', $ids),)->get();

        \LogActivity::add($request->user()->email.' ha actualizado el estado de los clientes con  ids '.$ids,json_encode($b), json_encode($request->all()));
        
        $a = Business::whereIn('bussId', explode(',', $ids),)->update($request->all());

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => $a,
        ], 200);
    }

    /*Funciones añadidas el 16/10/2022 */
    public function updateBusinessComment($ids, Request $request)
    {
        $b= Business::select()->whereIn('bussId', explode(',', $ids),)->get();

        \LogActivity::add($request->user()->email.' ha actualizado el comentario de los clientes con  ids '.$ids,json_encode($b), json_encode($request->all()));
        
        $a = Business::whereIn('bussId', explode(',', $ids),)->update($request->all());

        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => $a,
        ], 200);
    }


}
