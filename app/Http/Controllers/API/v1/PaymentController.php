<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Headquarter;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Teller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use PDOException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
    }

    public function all(Request $request){
        $params=[];
        $queryWhere='';

        /*$queryWhere.='and "hqId" = ?';
        array_push($params, (empty($request->hqId))?0:$request->hqId);
        */
        if($request->bussId>0){
            $queryWhere.='and "bussId"=? ';
            array_push($params,$request->bussId);
        } 
 
        if($request->hqId>0){
            $queryWhere.=' and "hqId"=? ';
            array_push($params,$request->hqId );
        }

        if($request->dateStart && $request->dateEnd){
            $queryWhere.=' and "payDatePrint" between ? and ? ';
            array_push($params,$request->dateStart, $request->dateEnd);
        }else if($request->dateStart){
            $queryWhere.=' and "payDatePrint">? ';
            array_push($params,$request->dateStart);
        }else if($request->dateEnd){
            $queryWhere.=' and "payDatePrint"<? ';
            array_push($params,$request->dateEnd);
        }

        if(!empty($request->wordLike)){
            $queryWhere.=' and ( "payClientName" like ? or "payClientRucOrDni" like ? or cast( "payNumber" as varchar) like ?)';
            $p='%'.$request->wordLike.'%';
            array_push($params,$p,$p,$p);
        }

        $data=Payment::select()
            ->whereRaw(' 1=1 '.$queryWhere,[$params])
            ->orderBy('payDatePrint', 'DESC')
            ->get();


        /*
            $data=AppointmentTemp::select()
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateStartAttention") as "elapsedSecondsStartAttention"'))
                ->addSelect(DB::raw(' EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" '))
                ->with('teller')
                ->with('category')
                ->whereRaw(' 1=1 '.$queryWhere,[$params])
                //->where('apptmState', 1)
                ->orderBy("elapsedSeconds", 'DESC')
                
                ->get();
        */
        return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente',
            'data'=>$data//$data//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=1 '.$queryWhere.' order by "elapsedSeconds" DESC',$params)
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=User::where('id', $request->user()->id)->with('tellers')->first();
        $r=$request->all();
                
        try {
            DB::connection()->beginTransaction();
            $p = Payment::create(array_merge($request->all(),['created_by'=>$user->id]));
            $p->paymentDetails()->createMany($request->paymentDetails);
            $p->dPaymentPaymentMethods()->createMany($request->dPaymentPaymentMethods);
            /*Para Facturar */
            $p->payDatePrint=DB::raw('now()');
            $p->userId=$user->id;
            $p->tellId=$user['tellers'][0]['tellId'];
            $p->updated_by=$user->id;
            $p->payState = 3;/*Facturado */

            $p->save();
            DB::connection()->commit();

            return response()->json([
                'res' => true,
                'msg' => 'Guardado correctamente',
                'data' => Payment::select()->with('paymentDetails')->with('dPaymentPaymentMethods')->where('payId', $p->payId)->first()

            ], 200);
        } catch (PDOException $e) {

            DB::connection()->rollBack();
            /*return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
                'data' => $e->getMessage(),
            ], 502);*/
            throw $e;
        }
        /*
        return response()->json([
            'res' => true,
            'msg' => 'Guardado correctamente',
            'data' => Payment::select()->with('paymentDetails')->where('payId',$p->payId )->first()

        ], 200);*/
        /*$data = [
            'titulo' => 'Styde.net',
            'token'=>123456
        ];

        return PDF::loadView('accounting.proof-of-payment', $data)
            ->stream('archivo.pdf');
        */

        /*$s=Payment::select()->with('paymentDetails')->where('payToken', $p->payToken)->first();

       $data = [
            'titulo' => 'Styde.net',
            'payment' => $s
        ];


        $path = base_path('resources/views/logo.png');
        //$path = base_path('storage/global/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data1 = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('b6', 'portrait')->loadView('accounting.proof-of-payment', compact('pic'), $data);

        return $pdf->stream();

     */
    }
    public function cancel($payId, Request $request){
        $p=Payment::where('payId',$payId )->first();

        $p->updated_by=$request->user()->id;
        $p->payIsCanceled=1/*1=Es cancelado, 2=No cancelado*/ ;
        $p->save();
        
        return response()->json([
            'res' => true,
            'msg' => 'Anulado correctamente',
            'data' => Payment::select()->with('paymentDetails')->with('dPaymentPaymentMethods')->where('payId', $p->payId)->first()

        ], 200);
    }

    public function setTicket($payId, Request $request){
        $p=Payment::where('payId',$payId )->first();

        $p->updated_by=$request->user()->id;
        $p->payTicketSN=$request->payTicketSN;
        
        $p->save();
        
        return response()->json([
            'res' => true,
            'msg' => 'Boleta actualizado correctamente',
            'data' => Payment::select()->with('paymentDetails')->with('dPaymentPaymentMethods')->where('payId', $p->payId)->first()

        ], 200);
    }
    public function setInvoice($payId, Request $request){
        $p=Payment::where('payId',$payId )->first();

        $p->updated_by=$request->user()->id;
        $p->payInvoiceSN=$request->payInvoiceSN;
        $p->save();
        
        return response()->json([
            'res' => true,
            'msg' => 'Factura actualizada correctamente',
            'data' => Payment::select()->with('paymentDetails')->with('dPaymentPaymentMethods')->where('payId', $p->payId)->first()

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

    public function proofOfPaymentJson($payToken)
    {
        try {
            $p = Payment::select()->with('paymentDetails')->where('payToken', $payToken)->first();
            $h = Headquarter::where('hqId', $p->hqId)->first();
            $t = Teller::where('tellId', $p->tellId)->first();
            $u = User::select('id', 'perId')->with('person')->first();

            $data = [
                'titulo' => 'Styde.net',
                'payment' => $p,
                'headquarter'=>$h, 
                'teller'=>$t,
                'user'=>$u
            ];

            return response()->json($data);

        } catch (Exception $e) {
            return 'Surgio un error, intente más tarde';
        }

      
    }

    public function proofOfPayment($payToken)
    {
        try {
            $p = Payment::select()->with('paymentDetails')->where('payToken', $payToken)->first();
            $h = Headquarter::where('hqId', $p->hqId)->first();
            $t = Teller::where('tellId', $p->tellId)->first();
            $u = User::select('id', 'perId')->with('person')->first();

            $data = [
                'titulo' => 'Styde.net',
                'payment' => $p,
                'headquarter'=>$h, 
                'teller'=>$t,
                'user'=>$u
            ];

            //return response()->json($data);

            $path = base_path('resources/views/logo.png');
            //$path = base_path('storage/global/logo.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper(array(0, 0, 220, 500))->loadView('accounting.proof-of-payment', compact('pic'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            return 'Surgio un error, intente más tarde';
        }

        /*/
        $path = base_path('resources/views/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data1 = file_get_contents($path);
        $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper(array(0, 0, 220, 500))->loadView('accounting.proof-of-payment', compact('pic'), $data);

        return $pdf->stream();*/

        //return PDF::loadView('accounting.proof-of-payment', $data)->stream('archivo.pdf');
        //return $pdf->download('mi-archivo.pdf');
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
