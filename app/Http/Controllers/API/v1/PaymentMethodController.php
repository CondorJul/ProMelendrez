<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\payment_method\AddPaymentMethodRequest;
use App\Http\Requests\payment_method\UpdPaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params=[];
        $queryWhere='';

        if($request->paymthdsState>0){
            $queryWhere.=' and "paymthdsState"=?';
            array_push($params,$request->paymthdsState );
        } 

        $data=PaymentMethod::select()
            ->whereRaw(' 1=1 '.$queryWhere,[$params])
            ->orderBy('paymthdsName','ASC')
            ->get();
        
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente.',
            'data'=>$data
        ],200);
           }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddPaymentMethodRequest $request)
    {
        $p = PaymentMethod::create($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => PaymentMethod::where('paymthdsId', $p->paymthdsId)->get()
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
    public function update(UpdPaymentMethodRequest $request, $id)
    {
        $hq = PaymentMethod::where('paymthdsId', $id)->first();
        $hq->paymthdsName = $request->paymthdsName;
        $hq->paymthdsState = $request->paymthdsState;


        $hq->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado correctamente',
            'data' => PaymentMethod::where('paymthdsId', $hq->paymthdsId)->get()
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
        $a = PaymentMethod::whereIn('paymthdsId', explode(',', $id),)->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Eliminado correctamente.',
            'data' => $a
        ], 200);
    }

    public function changeState($id, Request $request)
    {
        $q = PaymentMethod::where('paymthdsId', $id)->first();
        $q->paymthdsState = $request->paymthdsState;
        $q->save();
        return response()->json([
            'res' => true,
            'msg' => 'Actualizado Correctamente.',
        ], 200);
    }
}
