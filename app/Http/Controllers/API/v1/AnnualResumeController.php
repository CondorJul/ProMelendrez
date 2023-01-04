<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnnualResume;
use App\Models\AnnualResumeDetails;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class AnnualResumeController extends Controller
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

    public function find(Request $request, $arId){
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => AnnualResume::select()->with('annualResumeDetails')->where([
                'prdsId'=>$request->prdsId,
                'bussId'=>$request->bussId
            ])->orWhere('arId', $request->arId)->first()

        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    }

    public function create_update(Request $request)
    {
        $user= $request->user()->id;
        try {
            DB::connection()->beginTransaction();
            $ar=AnnualResume::where([
                'prdsId'=>$request->prdsId,
                'bussId'=>$request->bussId
            ])->first();

            if(!$ar){
                $p = AnnualResume::create(array_merge($request->all(),['created_by'=>$user->id]));
                $p->annualResumeDetails()->createMany($request->annualResumeDetails);
                \LogActivity::add($request->user()->email.' realizó el registro de resumen anual '.$p->arId, null, json_encode($request->all()));

            }elseif($ar->arState==1){
                $ar=AnnualResume::updateOrCreate([
                    'prdsId'=>$request->prdsId,
                    'bussId'=>$request->bussId
                ],[
                    'arDescription'=>$request->arDescription, 
                    'updated_by'=>$user->id
                ]);

                foreach ($request->annualResumeDetails as $key => $value) {
                    $t=AnnualResumeDetails::where([
                        'arId'=>$ar->arId , 
                        'ardMonth'=>$value->ardMonth
                    ])->first();
                    if($t){
                        $t->ardTaxBase=$request->ardTaxBase;
                        $t->ardTax=$request->ardTax;
                        $t->ardTotal=$request->ardTotal;
                        $t->ardPlame=$request->ardPlame;
                        $t->ardFee=$request->ardFee;
                        $t->updated_by=$user->id;
                        $t->save();
                    }else{
                        $p = AnnualResumeDetails::create(array_merge($value,['arId'=>$ar->arId, 'created_by'=>$user->id]));
                        
                    }
                    //"created_by" BIGINT,
                    //"updated_by" BIGINT,
                }
                \LogActivity::add($request->user()->email.' realizó la actualización de resumen anual '.$p->arId, null, json_encode($request->all()));

            }

            DB::connection()->commit();
            return response()->json([
                'res' => true,
                'msg' => 'Actualizado correctamente',
                'data' => AnnualResume::select()->with('annualResumeDetails')->where([
                    'prdsId'=>$request->prdsId,
                    'bussId'=>$request->bussId
                ])->first() //Payment::select()->with('paymentDetails')->with('dPaymentPaymentMethods')->where('payId', $p->payId)->first()

            ], 200);
        } catch (PDOException $e) {
            DB::connection()->rollBack();
            \LogActivity::add($request->user()->email.' trató de realizar un registro o actualización de resumen anual sin éxito.', null, json_encode($request->all()));
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
                'data' => $e->getMessage(),
            ], 502);
            

            throw $e;
        }

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

    
}
