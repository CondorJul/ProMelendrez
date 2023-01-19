<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\AnnualResume;
use App\Models\Business;
use App\Models\DBusinessPeriod;
use App\Models\Period;
use App\Models\Teller;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Arrays;

class ReportsController extends Controller
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

    public function reportControlMonitoring($periodId, $bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()->with('serviceProvided')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->with('serviceProvided.paymentDetails.payments.user.person')
                ->where('prdsId', $periodId)
                ->where('bussId', $bussId)->first();
            $p = Period::where('prdsId', $dbp->prdsId)->first();
            $b = Business::with('person')->where('bussId', $dbp->bussId)->first();

            $filter = array_filter(json_decode($dbp->serviceProvided), function ($item) {
                return (!($item->svId == 1 || $item->svId == 2));
            });

            $numFilas = count($filter);

            /*$otherServ = [
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];*/

            $data = [
                'd_business_period' => $dbp,
                'period' => $p,
                'business' => $b,
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.exercise-monitoring', compact('pic'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    public function controlMonitoringJson($periodId, $bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()->with('serviceProvided')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->with('serviceProvided.paymentDetails.payments.user.person')
                ->where('prdsId', $periodId)
                ->where('bussId', $bussId)->first();
            $p = Period::where('prdsId', $dbp->prdsId)->first();
            $b = Business::with('person')->where('bussId', $dbp->bussId)->first();

            $filter = array_filter(json_decode($dbp->serviceProvided), function ($item) {
                return (!($item->svId == 1 || $item->svId == 2));
            });

            $numFilas = count($filter);

            /*$otherServ = [
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];*/

            $data = [
                'd_business_period' => $dbp,
                'period' => $p,
                'business' => $b,
                'table1' => array_slice($filter, 0, ceil($numFilas / 2)),
                'table2' => array_slice($filter, ceil($numFilas / 2), $numFilas - 1)
            ];

            return response()->json($data);
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    public function reportAllPeriods($bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()
                ->join('periods', 'periods.prdsId', '=', 'd_bussines_periods.prdsId')
                ->with('serviceProvided')
                ->with('periods')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->where('bussId', $bussId)
                ->orderBy('periods.prdsNameShort', 'ASC')->get();
            $b = Business::with('person')->where('bussId', $bussId)->first();
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $meses[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $data = [
                'd_business_period' => $dbp,
                'business' => $b,
                'date' => $f
            ];
            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.png');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'portrait')->loadView('reports2.reports-alls-periods', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    public function reportAllPeriodsJson($bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()
                ->join('periods', 'periods.prdsId', '=', 'd_bussines_periods.prdsId')
                ->with('serviceProvided')
                ->with('periods')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->where('bussId', $bussId)->get();
            $b = Business::with('person')->where('bussId', $bussId)->first();
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $meses[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $data = [
                'd_business_period' => $dbp,
                'business' => $b,
                'date' => $f
            ];

            return response()->json($data);
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    public function getAllBussinesAndVisitorsByDate(Request $request)
    {

        $array = DB::select(
            '
            select * from ( SELECT date_trunc(\'day\', dd):: date "apptmDatePrint"
            FROM generate_series
                    ( CURRENT_DATE - INTERVAL \'1 months\'
                    , CURRENT_DATE
                    , \'1 day\'::interval) dd
            ) d


            left join
            (
            select
                date("apptmDateTimePrint")
                "_apptmDatePrint",
                sum(CASE WHEN "apptKindClient"=1 THEN 1 ELSE 0 END) as business,
                sum(CASE WHEN "apptKindClient"=2 THEN 1 ELSE 0 END) as visitors

                from appointment where "apptmDateTimePrint" >= (CURRENT_DATE - INTERVAL \'1 months\') GROUP BY date("apptmDateTimePrint") ORDER BY date("apptmDateTimePrint") desc) e on d."apptmDatePrint"=e."_apptmDatePrint"'
        );


        $seriesBusiness = array();
        $seriesBusiness['name'] = "Clientes";
        $seriesBusiness['series'] = array_map(function ($element) {
            return ['name' => $element->apptmDatePrint, 'value' => $element->business  | 0];
        }, $array);

        $seriesVisitors = array();
        $seriesVisitors['name'] = "Visitantes";
        $seriesVisitors['series'] = array_map(function ($element) {
            return ['name' => $element->apptmDatePrint, 'value' => $element->visitors | 0];
        }, $array);

        $result = array($seriesBusiness, $seriesVisitors);




        /*

        $array = DB::select(
            'select
                date("apptmDateTimePrint")
                "apptmDatePrint",
                sum(CASE WHEN "apptKindClient"=1 THEN 1 ELSE 0 END) as business,
                sum(CASE WHEN "apptKindClient"=2 THEN 1 ELSE 0 END) as visitors

                from appointment GROUP BY date("apptmDateTimePrint") ORDER BY date("apptmDateTimePrint") desc;'
        );


        $seriesBusiness = array();
        $seriesBusiness['name'] = "Clientes";
        $seriesBusiness['series'] = array_map(function ($element) {
            return ['name' => $element->apptmDatePrint, 'value' => $element->business];
        }, $array);

        $seriesVisitors = array();
        $seriesBusiness['name'] = "Visitantes";
        $seriesVisitors['series'] = array_map(function ($element) {
            return ['name' => $element->apptmDatePrint, 'value' => $element->visitors];
        }, $array);

        $arrayLineChart = array($seriesBusiness, $seriesVisitors);*/







        /** */
        $xAxisLabel = 'Últimos 30 dias';
        $yAxisLabel = 'Tickets de atención';

        $graph = array(
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'results' => $result
        );


        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $graph
        ], 200);
    }

    public function getPaymentsMethodsByTeller(Request $request)
    {

        $params = [];
        $queryWhere = '';
        if ($request->dateStart && $request->dateEnd) {
            $queryWhere .= ' and date("payDatePrint") between ? and ? ';
            array_push($params, $request->dateStart, $request->dateEnd);
        } else if ($request->dateStart) {
            $queryWhere .= ' and date("payDatePrint")>? ';
            array_push($params, $request->dateStart);
        } else if ($request->dateEnd) {
            $queryWhere .= ' and date("payDatePrint")<? ';
            array_push($params, $request->dateEnd);
        }

        $array = DB::select('SELECT p."tellId", (SELECT t."tellName" FROM teller t WHERE t."tellId"=p."tellId"), d."paymthdsId", m."paymthdsName", SUM(d."dppmAmount") AS total FROM payments p INNER JOIN d_payments_payment_methods d ON d."payId"=p."payId" INNER JOIN payment_methods m ON m."paymthdsId"=d."paymthdsId" where 1=1 ' . $queryWhere . ' GROUP BY p."tellId", d."paymthdsId", m."paymthdsName" ORDER BY p."tellId", d."paymthdsId"', $params);
        $array2 = Teller::select()->orderBy('tellId', 'ASC')->get();

        $array3 = array();

        foreach ($array2 as $key => $value) {
            $aux = array();
            $aux['name'] = $value->tellName;

            $aux1 = array_filter($array, function ($element) use ($value) {
                //return ['name' => $element->apptmDatePrint, 'value' => $element->business  | 0];
                return $element->tellId == $value->tellId;
            });
            $aux['series'] = array();
            $aux4 = array_map(function ($element1) {
                return ['name' => $element1->paymthdsName, 'value' => doubleval($element1->total)];
            }, $aux1);
            $aux['series'] = array_values($aux4);
            array_push($array3, $aux);
        }

        /*$seriesBusiness = array();
        $seriesBusiness['name'] = "Clientes";
        $seriesBusiness['series'] = array_map(function ($element) {
            return ['name' => $element->apptmDatePrint, 'value' => $element->business  | 0];
        }, $array);*/

        /*$seriesPayments = array();
        $seriesPayments['name'] = "Ventanilla 1";
        $seriesPayments['series'] = array_map(function ($element) {
            return ['name' => $element->paymthdsName, 'value' => $element->total | 0];
        }, $array);


        $result = array($seriesPayments);


        $xAxisLabel = 'Últimos 30 dias';
        $yAxisLabel = 'Tickets de atención';

        $graph = array(
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'results' => $result
        );*/


        //return $array3;
        $xAxisLabel = 'Ventanillas';
        $yAxisLabel = 'S/.';
        $legendTitle = 'Años';

        $graph = array(
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'legendTitle' => $legendTitle,
            'results' => $array3,
            'data' => $array
        );

        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $graph
        ], 200);
    }


    public function getBillingBalanceByMonth(Request $request)
    {

        $array = DB::select(
            '
            select extract(month from "payDatePrint") as month, extract(year from "payDatePrint") as year,sum("payTotal") as "sumPayTotal" from payments where "payIsCanceled"=2 AND extract(year from "payDatePrint")>=(extract(year from CURRENT_DATE)-2) group by year, month ORDER BY month asc, year asc
        '
        );

        $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        //$fecha = Carbon::parse(date('m/d/y'));
        //$mes = $meses[($fecha->format('m')) - 1];

        $seriesB = array();
        for ($i = 0; $i < count($nameMonths); $i++) {
            $idMonth = $i + 1;
            $serieB = array();
            $serieB['name'] = $nameMonths[$i];

            $aux1 = array_filter($array, function ($element) use ($idMonth) {
                //return ['name' => $element->apptmDatePrint, 'value' => $element->business  | 0];
                return $element->month == $idMonth; // element->tellId == $value->tellId;
            });


            $aux2 = array_map(function ($element) {
                return ['name' => $element->year, 'value' => doubleval($element->sumPayTotal)];
            }, $aux1);
            $serieB['series'] = array_values($aux2);
            array_push($seriesB, $serieB);
        }

        /** */
        $xAxisLabel = 'Meses';
        $yAxisLabel = 'S/.';

        $graph = array(
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'results' => $seriesB,
            'data' => $array

        );

        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $graph
        ], 200);
    }


    public function getTicketsByMonth(Request $request)
    {

        $array = DB::select(
            '
            select extract(month from "apptmDateTimePrint") as month, extract(year from "apptmDateTimePrint") as year,count(*) as "countTotal" from appointment where extract(year from "apptmDateTimePrint")>=(extract(year from CURRENT_DATE)-2) group by year, month ORDER BY month asc, year asc
        '
        );

        $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $seriesB = array();
        for ($i = 0; $i < count($nameMonths); $i++) {
            $idMonth = $i + 1;
            $serieB = array();
            $serieB['name'] = $nameMonths[$i];

            $aux1 = array_filter($array, function ($element) use ($idMonth) {
                return $element->month == $idMonth;
            });


            $aux2 = array_map(function ($element) {
                return ['name' => $element->year, 'value' => intval($element->countTotal)];
            }, $aux1);
            $serieB['series'] = array_values($aux2);
            array_push($seriesB, $serieB);
        }

        $xAxisLabel = 'Meses';
        $yAxisLabel = 'Tickets (n)';

        $graph = array(
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'results' => $seriesB,
            'data' => $array

        );

        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $graph
        ], 200);
    }

    public function getClientByState(Request $request)
    {

        $array = DB::select(
            '
            select "bussState", count(*) as "countTotal" from bussines group by "bussState" order by "bussState" asc
        '
        );

        $states = array("1" => "Activo", "2" => "Suspendido", "3" => "Retirado");

        $aux2 = array_map(function ($element) use ($states) {

            return ['name' => $states[$element->bussState], 'value' => intval($element->countTotal)];
        }, $array);


        $seriesB = array();


        /*for($i=0; $i<count($nameMonths);$i++){
            $idMonth=$i+1;
            $serieB=array();
            $serieB['name']=$nameMonths[$i];

            $aux1 = array_filter($array, function ($element) use ($idMonth) {
                return $element->month==$idMonth;
            });


            $aux2= array_map(function ($element) {
                return ['name' => $element->year, 'value' => intval($element->countTotal)];
            }, $aux1);
            $serieB['series'] =array_values($aux2);
            array_push($seriesB, $serieB);
        }*/

        $xAxisLabel = 'Meses';
        $yAxisLabel = 'Tickets (n)';

        $graph = array(
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'results' => $aux2,
            'data' => $array

        );

        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $graph
        ], 200);
    }



    public function getAnnualResumeByMonth(Request $request)
    {
        $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $arPrevious=AnnualResume::select()->with('period')->where([
            'prdsId'=>$request->prdsIdPrevious,
            'bussId'=>$request->bussId
        ])->first();


        $arCurrent=AnnualResume::select()->with('period')->where([
            'prdsId'=>$request->prdsIdCurrent,
            'bussId'=>$request->bussId
        ])->first();

        
        $arrayPrevious=DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc',[$arPrevious->arId]);
        $arrayCurrent=DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc',[$arCurrent->arId]);

        $seriePrevious=array();
        $serieCurrent=array();
        
        $seriePrevious['name']=$arPrevious->period->prdsNameShort;
        $serieCurrent['name']=$arCurrent->period->prdsNameShort;
        
        //previos
        $fPrevious=array_filter($arrayPrevious, function ($element) {
            return intval($element->ardMonth)<=12;
        });
        $valuesPrevious= array_map(function ($element) use ($nameMonths){
            return ['name' => $nameMonths[intval($element->ardMonth)-1], 'value' => doubleval($element->ardTotal)];
        }, $fPrevious);
        $seriePrevious['series']= array_values ($valuesPrevious);

        //actual
        $fCurrent=array_filter($arrayCurrent, function ($element) {
            return intval($element->ardMonth)<=12;
        });
        $valuesCurrent=array_map(function ($element) use ($nameMonths){
            return ['name' => $nameMonths[intval($element->ardMonth)-1], 'value' => doubleval($element->ardTotal)];
        }, $fCurrent);
        $serieCurrent['series']= array_values($valuesCurrent);
        
        $array=array($seriePrevious, $serieCurrent);



        
        //$u = User::select('id', 'perId')->with('person')->where('id', $p->userId)->first();

/*
        $array = DB::select('
            

            select extract(month from "payDatePrint") as month, extract(year from "payDatePrint") as year,sum("payTotal") as "sumPayTotal" from payments where "payIsCanceled"=2 AND extract(year from "payDatePrint")>=(extract(year from CURRENT_DATE)-2) group by year, month ORDER BY month asc, year asc
        '
        );

        $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        //$fecha = Carbon::parse(date('m/d/y'));
        //$mes = $meses[($fecha->format('m')) - 1];
        
        $seriesB = array();
        for($i=0; $i<count($nameMonths);$i++){
            $idMonth=$i+1;
            $serieB=array();
            $serieB['name']=$nameMonths[$i];

            $aux1 = array_filter($array, function ($element) use ($idMonth) {
                //return ['name' => $element->apptmDatePrint, 'value' => $element->business  | 0];
                return $element->month==$idMonth;// element->tellId == $value->tellId;
            });


            $aux2= array_map(function ($element) {
                return ['name' => $element->year, 'value' => doubleval($element->sumPayTotal)];
            }, $aux1);
            $serieB['series'] =array_values($aux2);
            array_push($seriesB, $serieB);
        }

        $xAxisLabel = 'Meses';
        $yAxisLabel = 'S/.';

        $graph = array(
            'xAxisLabel' => $xAxisLabel,
            'yAxisLabel' => $yAxisLabel,
            'results' => $seriesB, 
            'data'=>$array

        );
    */
    $xAxisLabel = 'Meses';
    $yAxisLabel = 'Tickets (n)';

    $graph = array(
        'xAxisLabel' => $xAxisLabel,
        'yAxisLabel' => $yAxisLabel,
        'results' => $array, 
        'data'=>$array

    );

        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $graph
        ], 200);
    }

    
    public function reportAnnualSummary($bussId)
    {
        try {
            $dbp = DBusinessPeriod::select()
                ->join('periods', 'periods.prdsId', '=', 'd_bussines_periods.prdsId')
                ->with('serviceProvided')
                ->with('periods')
                ->with('serviceProvided.services')
                ->with('serviceProvided.periodPayments')
                ->where('bussId', $bussId)
                ->orderBy('periods.prdsNameShort', 'ASC')->get();
            $b = Business::with('person')->where('bussId', $bussId)->first();
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $meses[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $data = [
                'd_business_period' => $dbp,
                'business' => $b,
                'date' => $f
            ];
            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.png');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.reports-annual-summary', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }
}
