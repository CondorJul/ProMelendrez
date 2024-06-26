<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\AnnualResume;
use App\Models\Business;
use App\Models\DBusinessPeriod;
use App\Models\Period;
use App\Models\Task;
use App\Models\Teller;
use App\Models\User;
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

            $path1 = base_path('resources/views/icon.jpg');
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

        $array = DB::select('SELECT p."tellId", (SELECT t."tellName" FROM teller t WHERE t."tellId"=p."tellId"), d."paymthdsId", m."paymthdsName", SUM(d."dppmAmount") AS total FROM payments p INNER JOIN d_payments_payment_methods d ON d."payId"=p."payId" INNER JOIN payment_methods m ON m."paymthdsId"=d."paymthdsId" where "payIsCanceled"=2  ' . $queryWhere . ' GROUP BY p."tellId", d."paymthdsId", m."paymthdsName" ORDER BY p."tellId", d."paymthdsId"', $params);
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

        $arPrevious = AnnualResume::select()->with('period')->where([
            'prdsId' => $request->prdsIdPrevious,
            'bussId' => $request->bussId
        ])->first();


        $arCurrent = AnnualResume::select()->with('period')->where([
            'prdsId' => $request->prdsIdCurrent,
            'bussId' => $request->bussId
        ])->first();


        $arrayPrevious = DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc', [$arPrevious->arId]);
        $arrayCurrent = DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc', [$arCurrent->arId]);

        $seriePrevious = array();
        $serieCurrent = array();

        $seriePrevious['name'] = $arPrevious->period->prdsNameShort;
        $serieCurrent['name'] = $arCurrent->period->prdsNameShort;

        //previos
        $fPrevious = array_filter($arrayPrevious, function ($element) {
            return intval($element->ardMonth) <= 12;
        });
        $valuesPrevious = array_map(function ($element) use ($nameMonths) {
            return ['name' => $nameMonths[intval($element->ardMonth) - 1], 'value' => doubleval($element->ardTotal)];
        }, $fPrevious);
        $seriePrevious['series'] = array_values($valuesPrevious);

        //actual
        $fCurrent = array_filter($arrayCurrent, function ($element) {
            return intval($element->ardMonth) <= 12;
        });
        $valuesCurrent = array_map(function ($element) use ($nameMonths) {
            return ['name' => $nameMonths[intval($element->ardMonth) - 1], 'value' => doubleval($element->ardTotal)];
        }, $fCurrent);
        $serieCurrent['series'] = array_values($valuesCurrent);

        $array = array($seriePrevious, $serieCurrent);




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
            'data' => $array

        );

        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => $graph
        ], 200);
    }


    public function reportAnnualSummary(Request $request)
    {
        try {
            $b = Business::with('person')->where('bussId', $request->bussId)->first();
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Total", "Balance Anual");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $nameMonths[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $sales1 = AnnualResume::select()->with('period')->with(array('annualResumeDetails' => function ($query) {
                $query->orderBy('ardMonth', 'ASC');
            }))
                ->where([
                    'prdsId' => $request->prdsIdPrevious,
                    'bussId' => $request->bussId
                ])
                ->first();

            $aux = array_map(function ($element) use ($nameMonths) {
                return array_merge($element, ['nameMonth' => $nameMonths[intval($element['ardMonth']) - 1]]);
            }, $sales1->annualResumeDetails->toArray());

            $sales1 = array_merge($sales1->toArray(), ['annualResumeDetails' => $aux]);


            $sales2 = AnnualResume::select()->with('period')->with(array('annualResumeDetails' => function ($query) {
                $query->orderBy('ardMonth', 'ASC');
            }))
                ->where([
                    'prdsId' => $request->prdsIdCurrent,
                    'bussId' => $request->bussId
                ])
                ->first();

            $aux1 = array_map(function ($element) use ($nameMonths) {
                return array_merge($element, ['nameMonth' => $nameMonths[intval($element['ardMonth']) - 1]]);
            }, $sales2->annualResumeDetails->toArray());

            $sales2 = array_merge($sales2->toArray(), ['annualResumeDetails' => $aux1]);

            $lineaMonths = array();
            $lineaMonths = array_slice(array_values($nameMonths),0,12, true);

            $arPrevious = AnnualResume::select()->with('period')->where([
                'prdsId' => $request->prdsIdPrevious,
                'bussId' => $request->bussId
            ])->first();

            $arCurrent = AnnualResume::select()->with('period')->where([
                'prdsId' => $request->prdsIdCurrent,
                'bussId' => $request->bussId
            ])->first();

            $arrayPrevious = DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc', [$arPrevious->arId]);
            $arrayCurrent = DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc', [$arCurrent->arId]);

            $auxPrev = array_map(function ($element) {
                return doubleval($element->ardTotal);
            }, $arrayPrevious);
            $result1 = array_slice($auxPrev,0,12, true);

            $auxCur = array_map(function ($element) {
                return doubleval($element->ardTotal);
            }, $arrayCurrent);
            $result2 = array_slice($auxCur,0,12, true);

            $result3 = array();
            $result3 = $lineaMonths;

            $datos = array(
                'type'=>'line',
                'data'=>array(
                    'labels'=>$lineaMonths,
                    'datasets'=>[array(
                        'label' => $arPrevious->period->prdsNameShort,
                        'data' => $result1,
                        'fill' => false,
                        'borderColor' => 'red'
                    ),
                    array(
                        'label' => $arCurrent->period->prdsNameShort,
                        'data' => $result2,
                        'fill' => false,
                        'borderColor' => 'green'
                    )]
                )
            );

            $data = [
                'business' => $b,
                'date' => $f,
                'salesPrev' => $sales1,
                'salesCur' => $sales2,
                'data' => json_encode($datos),
                'evaluateIsset' => function ($num) {
                    return isset($num) ? $num : '-';
                }
            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.jpg');
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



    /*
    public function myFormatDJJson(Request $request)
    {
        //seleeciona el mes
        $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        //consulta base de datos
        $teller=Teller:: select()->where('tellId', $request->tellId)->get();
        $businesses=Business::selectRaw('*, RIGHT("bussRUC",1) as "_lastDigit" ')->whereRaw('"tellId"=?  and "bussState"=?', [$request->tellId, $request->bussState]) ->orderByRaw(' "_lastDigit" asc, "bussName" asc')->get();
        $period=Period::select()->where('prdsId', $request->prdsId)->first();

        $dataGrouped=array();


        for ($i=0; $i <10 ; $i++) {
            $aux1 = array_filter($businesses->toArray(), function ($element) use ($i) {
                return intval($element['_lastDigit']) == intval($i);
            });
            $dataGrouped["digit-".$i]=array_values($aux1);
        }



        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data'=>[
                'teller' => $teller,
                'businesses' => $businesses,
                'groupeds'=>$dataGrouped,
                'period'=>$period,
                'month'=>$nameMonths[$request->month-1]
            ]

        ], 200);
    }*/




    public function reportAnnualSummaryJson(Request $request)
    {
        try {
            $b = Business::with('person')->where('bussId', $request->bussId)->first();
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Total", "Balance Anual");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $nameMonths[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $sales1 = AnnualResume::select()->with('period')->with(array('annualResumeDetails' => function ($query) {
                $query->orderBy('ardMonth', 'ASC');
            }))
                ->where([
                    'prdsId' => $request->prdsIdPrevious,
                    'bussId' => $request->bussId
                ])
                ->first();

            $aux = array_map(function ($element) use ($nameMonths) {
                return array_merge($element, ['nameMonth' => $nameMonths[intval($element['ardMonth']) - 1]]);
            }, $sales1->annualResumeDetails->toArray());

            $sales1 = array_merge($sales1->toArray(), ['annualResumeDetails' => $aux]);


            $sales2 = AnnualResume::select()->with('period')->with(array('annualResumeDetails' => function ($query) {
                $query->orderBy('ardMonth', 'ASC');
            }))
                ->where([
                    'prdsId' => $request->prdsIdCurrent,
                    'bussId' => $request->bussId
                ])
                ->first();

            $aux1 = array_map(function ($element) use ($nameMonths) {
                return array_merge($element, ['nameMonth' => $nameMonths[intval($element['ardMonth']) - 1]]);
            }, $sales2->annualResumeDetails->toArray());

            $sales2 = array_merge($sales2->toArray(), ['annualResumeDetails' => $aux1]);

            $lineaMonths = array();
            $lineaMonths = array_slice(array_values($nameMonths),0,12, true);

            $arPrevious = AnnualResume::select()->with('period')->where([
                'prdsId' => $request->prdsIdPrevious,
                'bussId' => $request->bussId
            ])->first();

            $arCurrent = AnnualResume::select()->with('period')->where([
                'prdsId' => $request->prdsIdCurrent,
                'bussId' => $request->bussId
            ])->first();

            $arrayPrevious = DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc', [$arPrevious->arId]);
            $arrayCurrent = DB::select('SELECT "ardMonth", "ardTotal" FROM annual_resume_details where "arId"=? order by "ardMonth" asc', [$arCurrent->arId]);


            $auxPrev = array_map(function ($element) {
                return doubleval($element->ardTotal);
            }, $arrayPrevious);
            $result1 = array_slice($auxPrev,0,12, true);

            $auxCur = array_map(function ($element) {
                return doubleval($element->ardTotal);
            }, $arrayCurrent);
            $result2 = array_slice($auxCur,0,12, true);

            $result3 = array();
            $result3 = $lineaMonths;

            $datos = array(
                'type'=>'line',
                'data'=>array(
                    'labels'=>$result3,
                    'datasets'=>[array(
                        'label' => 'Dogs',
                        'data' => $result1,
                        'fill' => false,
                        'borderColor' => 'blue'
                    ),
                    array(
                        'label' => 'Dogs',
                        'data' => $result2,
                        'fill' => false,
                        'borderColor' => 'blue'
                    )]
                )
            );


            $data = [
                'business' => $b,
                'date' => $f,
                'salesPrev' => $sales1,
                'salesCur' => $sales2,
                'data' => $datos,
                'empty2' => function ($num) {
                    return isset($num) ? $num : '-';
                }
            ];

            return response()->json($data);
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }




    public function reportFormatDeclarationByLastDigit(Request $request)
    {
        try {
            //seleeciona el mes
            $nameMonths = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
            $period = Period::select()->where('prdsId', $request->prdsId)->first();
            $year=$period->prdsNameShort;
            $month=$request->month;
            $ln=$request->ln;

            $totalMonths=$year*12+$month;
            $bussState='1';

            //consulta base de datos
            $teller = Teller::select()->with('user.person')->where('tellId', $request->tellId)->first();

            /** */
            $dbmMonthBefore = 12;
            $prdsIdBefore=$request->prdsId;
            $yearBefore = $year;
            if($month>=2){
                $dbmMonthBefore = $month-1;
            }else{
                $p= Period::select()->where('prdsNameShort',$year-1)->first();
                $prdsIdBefore = $p->prdsId;
                $yearBefore =  $p->prdsNameShort;
            }

      

            $params = [$bussState,  $totalMonths,$bussState, $totalMonths, $totalMonths];

            $paramsSql='';
            if($request->tellId>0){
                $paramsSql=' and
                "tellId"=? ';
                $params[] = $request->tellId;

            }
            if($request->ln>=0){
                $paramsSql.=' and 
                (RIGHT("bussRUC",1)=? or -1=?) ';
                $params[]=$request->ln;
                $params[]=$request->ln;

            }
            $businesses = Business::selectRaw('*, RIGHT("bussRUC",1) as "_lastDigit" ')->with('teller')
            //->whereRaw('"tellId"=?  and "bussState"=?', [$request->tellId, $request->bussState])
            //en el where comparamos dos fechas, la fecha actual tiene que ser mayor a la fecha ingresada
            ->whereRaw('
                        
                        (
                            (
                                "bussState"=?/*Activo*/
                                and (extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate")<=?)
                            )
                            OR
                            EXISTS
                            (
                                select * from business_states
                                    where business_states."bussId" = bussines."bussId" and business_states."bussState"=?
                                    and
                                    (
                                        extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate"))<=?
                                        and ?<=(extract(YEAR from "bussStateDateNew")*12+extract(MONTH from "bussStateDateNew")
                                    )
                            )
                        ) 
                        
                        '.$paramsSql, $params)
                    ->orderByRaw(' "_lastDigit" asc, "bussName" asc')
                    ->get();



                    $dBusinessPeriodBefore=DBusinessPeriod::selectRaw('d_bussines_periods.*, bussines."bussRUC", bussines."bussName", RIGHT("bussRUC",1) as "_lastDigit"')
                    ->join('bussines', 'bussines.bussId', '=', 'd_bussines_periods.bussId')
                    ->with([ 'doneByMonths'=>function($query) use($dbmMonthBefore){
                        $query->where('dbmMonth',$dbmMonthBefore);
                    },
                    'doneByMonths.dDoneByMonthTasks'=>function($query3){
                        $query3->orderBy('tsksId','asc');
                    } ,
                    'doneByMonths.dDoneByMonthTasks.task'])
                    /*->with(['business'=>function($query){
                        $query->orderByRaw(' RIGHT("bussRUC",1) ASC, "bussName" asc ');
                    }])*/
                    ->with('business')
                    ->where('prdsId',$prdsIdBefore)  
                    ->whereRaw('(RIGHT("bussRUC",1)=? or -1=?) ', [$ln, $ln])
                    //->has('doneByMonths')
                    ->whereHas('doneByMonths', function($query) use ($dbmMonthBefore){
                        $query->where('dbmMonth',$dbmMonthBefore);
                    })
                    /*->whereHas('business', function($query) use ($ln){
                        $query->whereRaw(' (RIGHT("bussRUC",1)=? or -1=?) ',[$ln,$ln]);
                    })*/
                    ->orderByRaw('RIGHT("bussRUC",1) ASC, "bussName" asc ')
                    ->get();

                    $dBusinessPeriodBeforeArray = $dBusinessPeriodBefore->toArray();
     
                    $dBusinessPeriodBeforeArrayC= array_reduce($dBusinessPeriodBeforeArray, function($array, $obj) {
                          $array['bussRUC_'.$obj['bussRUC']]=$obj; 
                          return $array;
                    },[]);
    
                    $businesses = $businesses->map(function ($item) use ($dBusinessPeriodBeforeArrayC) {
                        $item['doneByMonthsBefore'] = $dBusinessPeriodBeforeArrayC['bussRUC_'.$item['bussRUC']]['doneByMonths']??[];
                        return $item;
                    });

                   
               



            

            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $nameMonths[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $dataGrouped = array();




            if($ln>=0 && $ln<=9/*Entonces solo regres de un digito */){
                //*NO hay necesidad de agrupar por ultimo digito por que solo es uno */
                $aux1 = $businesses->toArray();
                //$aux2 = array_values($aux1);
                $aux2 = array_values($aux1);
                $temp2 = array_merge($aux2, [['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => '', 'bussRUC' => ''], ['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => '', 'bussRUC' => '']]);
                $temp = array();
                $temp['name'] = 'RUC ' . $ln;
                $temp['values'] = $aux1;
                /*aqui guardamos el nombre de mes para luego plasmarlo en el formato de forma mas facil */
                $temp['month']= strtoupper($nameMonths[$month - 1]);

                $temp['month_before'] =  strtoupper($nameMonths[$dbmMonthBefore - 1]);
                $temp['year'] = $year ;
                $temp['year_before'] =  $yearBefore;

                $dataGrouped["digit-" . $ln] = $temp;
            }
            if($ln==-1){
                //agrupa por ultimo digito
                for ($i = 0; $i < 10; $i++) {
                    $aux1 = array_filter($businesses->toArray(), function ($element) use ($i) {
                        return intval($element['_lastDigit']) == intval($i);
                    });
                    $aux2 = array_values($aux1);
                    $temp2 = array_merge($aux2, [['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => '', 'bussRUC' => ''], ['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => '', 'bussRUC' => '']]);
                    $temp = array();
                    $temp['name'] = 'RUC ' . $i;
                    $temp['values'] = $temp2;
                    $temp['month']= strtoupper($nameMonths[$month - 1]);
                    
                    $temp['month_before'] =  strtoupper($nameMonths[$dbmMonthBefore - 1]);
                    $temp['year'] = $year ;
                    $temp['year_before'] =  $yearBefore;
    
                    $dataGrouped["digit-" . $i] = $temp;
                }
            }
            

            
            $users=User::select('id', 'perId')
            ->with('person')
            ->get();

            $data = [
                'teller' => $teller,
                'businesses' => $businesses,
                'groupeds' => $dataGrouped,
                'period' => $period,
                'users' => $users,
                'month' => $nameMonths[$request->month - 1],
                'date' => $f,
                'substring' => function ($str) {
                    $a=[
                        ['oldWord'=>'EMPRESA', 'newWord'=>'EMP'],
                        ['oldWord'=>'COMUNAL', 'newWord'=>'COMU'],
                        ['oldWord'=>'SERVICIOS', 'newWord'=>'SERV'],
                        ['oldWord'=>'MÚLTIPLES', 'newWord'=>'MÚLT'],
                        ['oldWord'=>'MULTIPLES', 'newWord'=>'MULT'],
                        ['oldWord'=>'TRANSPORTES', 'newWord'=>'TRANSP'],
                        ['oldWord'=>'SOCIEDAD ANONIMA CERRADA', 'newWord'=>'SAC'],
                        ['oldWord'=>'ASOCIACION', 'newWord'=>'ASOC'],
                        ['oldWord'=>'PRODUCTORES', 'newWord'=>'PRODUCT'],
                        ['oldWord'=>'AGROPECUARIOS', 'newWord'=>'AGROPE'],
                        ['oldWord'=>'CORPORACION', 'newWord'=>'CORPOR'],
                    ];
                    foreach ($a as $key => $value) {
                        $str=str_replace($value['oldWord'], $value['newWord'],$str);
                    }
                    //str_replace("world","Peter","Hello world!");
                   // return \Illuminate\Support\Str::limit($str, 31, $end='');
                    return substr( $str, 0, 31);
                }, 
                'getUserName'=>function($users, $id){
                    $aux = array_filter($users->toArray(), function ($element) use ($id) {
                        return intval($element['id']) == intval($id);
                    });
                    $aux1=array_values($aux);
                    $user=(count($aux1) >0)?$aux1[0]:null;
                    $perName=($user!=null)?explode(" ",$user['person']['perName'])[0]:'-----';
                    return ucwords($perName);
                },
                'getBussRegimeName'=>function($bussRegime){
                    if(!$bussRegime) return "";
                    $a=array("1"=>"ESPECIAL", "2"=>"GENERAL", "3"=>"MYPE");
                    return $a[$bussRegime];
                },
                'getBussFileKindName'=>function($bussFileKind){
                    if(!$bussFileKind) return "";
                    $a=array("1"=>"Archivador", "2"=>"Folder");
                    return $a[$bussFileKind];
                },
                'getBussKindBookAccName'=>function($bussKindBookAcc){
                    if(!$bussKindBookAcc) return "";
                    $a=array("1"=>"Electronico", "2"=>"Computarizado");
                    return $a[$bussKindBookAcc];
                }
            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.jpg');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.reports-format-declaration-by-last-digit', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    public function reportFormatDeclaration(Request $request)
    {
        try {
            //seleeciona el mes
            $nameMonths = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
            $period = Period::select()->where('prdsId', $request->prdsId)->first();
            $year=$period->prdsNameShort;
            $month=$request->month;
            $totalMonths=$year*12+$month;
            $bussState='1';

            //consulta base de datos
            $teller = Teller::select()->with('user.person')->where('tellId', $request->tellId)->first();
            $businesses = Business::selectRaw('*, RIGHT("bussRUC",1) as "_lastDigit" ')
            //->whereRaw('"tellId"=?  and "bussState"=?', [$request->tellId, $request->bussState])
            //en el where comparamos dos fechas, la fecha actual tiene que ser mayor a la fecha ingresada
            ->whereRaw('"tellId"=?
                        and
                        (
                            (
                                "bussState"=?/*Activo*/
                                and (extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate")<=?)
                            )
                            OR
                            EXISTS
                            (
                                select * from business_states
                                    where business_states."bussId" = bussines."bussId" and business_states."bussState"=?
                                    and
                                    (
                                        extract(YEAR from "bussStateDate")*12+extract(MONTH from "bussStateDate"))<=?
                                        and ?<=(extract(YEAR from "bussStateDateNew")*12+extract(MONTH from "bussStateDateNew")
                                    )
                            )
                        )', [$request->tellId, $bussState,  $totalMonths,$bussState, $totalMonths, $totalMonths])
            ->orderByRaw(' "_lastDigit" asc, "bussName" asc')
            ->get();

            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $nameMonths[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $dataGrouped = array();


            for ($i = 0; $i < 10; $i++) {
                $aux1 = array_filter($businesses->toArray(), function ($element) use ($i) {
                    return intval($element['_lastDigit']) == intval($i);
                });
                $aux2 = array_values($aux1);
                $temp2 = array_merge($aux2, [['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => ''], ['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => '']]);
                $temp = array();
                $temp['name'] = 'RUC ' . $i;
                $temp['values'] = $temp2;
                $dataGrouped["digit-" . $i] = $temp;
            }

            $data = [
                'teller' => $teller,
                'businesses' => $businesses,
                'groupeds' => $dataGrouped,
                'period' => $period,
                'month' => $nameMonths[$request->month - 1],
                'date' => $f,
                'substring' => function ($str) {
                    $a=[
                        ['oldWord'=>'EMPRESA', 'newWord'=>'EMP'],
                        ['oldWord'=>'COMUNAL', 'newWord'=>'COMU'],
                        ['oldWord'=>'SERVICIOS', 'newWord'=>'SERV'],
                        ['oldWord'=>'MÚLTIPLES', 'newWord'=>'MÚLT'],
                        ['oldWord'=>'MULTIPLES', 'newWord'=>'MULT'],
                        ['oldWord'=>'TRANSPORTES', 'newWord'=>'TRANSP'],
                        ['oldWord'=>'SOCIEDAD ANONIMA CERRADA', 'newWord'=>'SAC'],
                        ['oldWord'=>'ASOCIACION', 'newWord'=>'ASOC'],
                        ['oldWord'=>'PRODUCTORES', 'newWord'=>'PRODUCT'],
                        ['oldWord'=>'AGROPECUARIOS', 'newWord'=>'AGROPE'],
                        ['oldWord'=>'CORPORACION', 'newWord'=>'CORPOR'],
                    ];
                    foreach ($a as $key => $value) {
                        $str=str_replace($value['oldWord'], $value['newWord'],$str);
                    }
                    //str_replace("world","Peter","Hello world!");
                    return \Illuminate\Support\Str::limit($str, 31, $end='');
                    //return substr( $str, 0, 31);
                }
            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.jpg');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'portrait')->loadView('reports2.reports-format-declaration', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    public function myFormatDJJson(Request $request)
    {
        //seleeciona el mes
        $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        //consulta base de datos
        $teller = Teller::select()->with('user.person')->where('tellId', $request->tellId)->first();
        $businesses = Business::selectRaw('*, RIGHT("bussRUC",1) as "_lastDigit" ')
        ->whereRaw('"tellId"=?  and "bussState"=?', [$request->tellId, $request->bussState])
        ->orderByRaw(' "_lastDigit" asc, "bussName" asc')->get();
        $period = Period::select()->where('prdsId', $request->prdsId)->first();

        $dataGrouped = array();


        for ($i = 0; $i < 10; $i++) {
            $aux1 = array_filter($businesses->toArray(), function ($element) use ($i) {
                return intval($element['_lastDigit']) == intval($i);
            });
            $aux2 = array_values($aux1);
            $temp2 = array_merge($aux2, [['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => ''], ['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => '']]);
            $temp = array();
            $temp['name'] = 'RUC ' . $i;
            $temp['values'] = $temp2;
            $dataGrouped["digit-" . $i] = $temp;
        }



        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => [
                'teller' => $teller,
                'businesses' => $businesses,
                'groupeds' => $dataGrouped,
                'period' => $period,
                'month' => $nameMonths[$request->month - 1]
            ]

        ], 200);
    }


    public function tasksCompletedJSON(Request $request)
    {


        $businesses=Business::select()
            ->with('dBussinesPeriods.doneByMonth.dDoneByMonthTasks.task')
            ->with('businessStates')


       /*    ->whereHas('services.serviceType
            's', function ($query) use ($id) {
                return $query->where('id', $id);
            })*/
            ->get();


        //$tasks=Task::select()->get();

        return response()->json([
            'data'=>$businesses,
            //'tasks'=>$tasks
        ]);

        /*
        $place = Place::with('services.serviceTypes')
            ->whereIn('id', $ids)
            ->whereHas('services.serviceTypes', function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->get();*/



        /*        $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $teller = Teller::select()->with('user.person')->where('tellId', $request->tellId)->first();
        $businesses = Business::selectRaw('*, RIGHT("bussRUC",1) as "_lastDigit" ')->whereRaw('"tellId"=?  and "bussState"=?', [$request->tellId, $request->bussState])->orderByRaw(' "_lastDigit" asc, "bussName" asc')->get();
        $period = Period::select()->where('prdsId', $request->prdsId)->first();

        $dataGrouped = array();


        for ($i = 0; $i < 10; $i++) {
            $aux1 = array_filter($businesses->toArray(), function ($element) use ($i) {
                return intval($element['_lastDigit']) == intval($i);
            });
            $aux2 = array_values($aux1);
            $temp2 = array_merge($aux2, [['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => ''], ['bussName' => '', 'bussFileNumber' => '', 'bussRegime' => '']]);
            $temp = array();
            $temp['name'] = 'RUC ' . $i;
            $temp['values'] = $temp2;
            $dataGrouped["digit-" . $i] = $temp;
        }



        return response()->json([
            'res' => true,
            'msg' => 'Listado correctamente',
            'data' => [
                'teller' => $teller,
                'businesses' => $businesses,
                'groupeds' => $dataGrouped,
                'period' => $period,
                'month' => $nameMonths[$request->month - 1]
            ]

        ], 200);

        */

    }



    public function reportTasks(Request $request)
    {
        try {
            $b = Business::with('person')->where('bussId', $request->bussId)->first();
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Total", "Balance Anual");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $nameMonths[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');

            $data = [
                'business' => $b,
                'date' => $f
            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.jpg');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.reports-tasks', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }


    public function reportTasksBySubPeriod(Request $request)
    {
        try {
           //Seleccionamos y stablecemos los datos generales
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Total", "Balance Anual");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $nameMonths[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');


            //$b = Business::with('person')->where('bussId', $request->bussId)->first();

            /*$businesses=Business::select()
                ->with('dBussinesPeriods.doneByMonths.dDoneByMonthTasks.task')
                ->with('businessStates')
                ->get();*/

            $prdsId=$request->prdsId;
            $dbmMonth=$request->dbmMonth;
            $ln=$request->ln;

            $period=Period::select()->where('prdsId',$prdsId)->first();
            $year=$period->prdsNameShort;

            /*$dBusinessPeriod=DBusinessPeriod::select()
                ->with([ 'doneByMonths'=>function($query) use($dbmMonth){
                    $query->where('dbmMonth',$dbmMonth);
                },
                'doneByMonths.dDoneByMonthTasks'=>function($query3){
                    $query3->orderBy('tsksId','asc');
                } ,
                'doneByMonths.dDoneByMonthTasks.task'])
                ->with(['business'])
                ->where('prdsId',$prdsId)
                ->whereHas('doneByMonths')
                ->get();*/
                $dBusinessPeriod=DBusinessPeriod::selectRaw('d_bussines_periods.*, bussines."bussRUC", bussines."bussName", RIGHT("bussRUC",1) as "_lastDigit"')
                ->join('bussines', 'bussines.bussId', '=', 'd_bussines_periods.bussId')
                ->with([ 'doneByMonths'=>function($query) use($dbmMonth){
                    $query->where('dbmMonth',$dbmMonth);
                },
                'doneByMonths.dDoneByMonthTasks'=>function($query3){
                    $query3->orderBy('tsksId','asc');
                } ,
                'doneByMonths.dDoneByMonthTasks.task'])
                /*->with(['business'=>function($query){
                    $query->orderByRaw(' RIGHT("bussRUC",1) ASC, "bussName" asc ');
                }])*/
                ->with('business')
                ->where('prdsId',$prdsId)
                ->whereRaw('(RIGHT("bussRUC",1)=? or -1=?) ', [$ln, $ln])
                //->has('doneByMonths')
                ->whereHas('doneByMonths', function($query) use ($dbmMonth){
                    $query->where('dbmMonth',$dbmMonth);
                })
                /*->whereHas('business', function($query) use ($ln){
                    $query->whereRaw(' (RIGHT("bussRUC",1)=? or -1=?) ',[$ln,$ln]);
                })*/
                ->orderByRaw('RIGHT("bussRUC",1) ASC, "bussName" asc ')
                ->get();

                $dataGrouped = array();
                if($ln>=0 && $ln<=9/*Entonces solo regres de un digito */){
                    //*NO hay necesidad de agrupar por ultimo digito por que solo es uno */
                    $aux1 = $dBusinessPeriod;
                    //$aux2 = array_values($aux1);
                    $temp = array();
                    $temp['name'] = 'RUC ' . $ln;
                    $temp['values'] = $aux1;
                    /*aqui guardamos el nombre de mes para luego plasmarlo en el formato de forma mas facil */
                    $temp['month']= strtoupper($nameMonths[$dbmMonth - 1]);

                    $dataGrouped["digit-" . $ln] = $temp;
                }
                if($ln==-1){
                    //agrupa por ultimo digito
                    for ($i = 0; $i < 10; $i++) {
                        $aux1 = array_filter($dBusinessPeriod->toArray(), function ($element) use ($i) {
                            return intval($element['_lastDigit']) == intval($i);
                        });
                        $aux2 = array_values($aux1);
                        $temp = array();
                        $temp['name'] = 'RUC ' . $i;
                        $temp['values'] = $aux1;
                        /*aqui guardamos el nombre de mes para luego plasmarlo en el formato de forma mas facil */
                        $temp['month']= strtoupper($nameMonths[$dbmMonth - 1]);

                        $dataGrouped["digit-" . $i] = $temp;
                    }
                }


            $users=User::select('id', 'perId')
                ->with('person')
                ->get();

            $data = [
                '_dBusinessPeriods' => $dBusinessPeriod,
                'groupeds' => $dataGrouped,
                'users' => $users,
                'date' => $f,
                'getUserName'=>function($users, $id){
                    $aux = array_filter($users->toArray(), function ($element) use ($id) {
                        return intval($element['id']) == intval($id);
                    });
                    $aux1=array_values($aux);
                    $user=(count($aux1) >0)?$aux1[0]:null;
                    $perName=($user!=null)?explode(" ",$user['person']['perName'])[0]:'-----';
                    return ucwords($perName);
                },
                'getBussRegimeName'=>function($bussRegime){
                    if(!$bussRegime) return "";
                    $a=array("1"=>"ESPECIAL", "2"=>"GENERAL", "3"=>"MYPE");
                    return $a[$bussRegime];
                },
                'getBussFileKindName'=>function($bussFileKind){
                    if(!$bussFileKind) return "";
                    $a=array("1"=>"Archivador", "2"=>"Folder");
                    return $a[$bussFileKind];
                },
                'getBussKindBookAccName'=>function($bussKindBookAcc){
                    if(!$bussKindBookAcc) return "";
                    $a=array("1"=>"Electronico", "2"=>"Computarizado");
                    return $a[$bussKindBookAcc];
                }



            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.jpg');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.reports-tasks-by-sub-period', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

    

    public function reportTasksBySubPeriodWithBeforeMonth(Request $request)
    {
        try {
           //Seleccionamos y stablecemos los datos generales
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $nameMonths = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre", "Total", "Balance Anual");
            $fecha = Carbon::parse(date('m/d/y'));
            $mes = $nameMonths[($fecha->format('m')) - 1];
            $f = $fecha->format('d') . ' de ' . $mes . ' de ' . $fecha->format('Y');
            //$b = Business::with('person')->where('bussId', $request->bussId)->first();

            /*$businesses=Business::select()
                ->with('dBussinesPeriods.doneByMonths.dDoneByMonthTasks.task')
                ->with('businessStates')
                ->get();*/

            $prdsId=$request->prdsId;
            $dbmMonth=$request->dbmMonth;
            $ln=$request->ln;

            $period=Period::select()->where('prdsId',$prdsId)->first();
            $year=$period->prdsNameShort;
            
            /** */
            $dbmMonthBefore = 12;
            $prdsIdBefore=$request->prdsId;
            $yearBefore = $year;
            if($dbmMonth>=2){
                $dbmMonthBefore = $dbmMonth-1;
            }else{
                $p= Period::select()->where('prdsNameShort',$year-1)->first();
                $prdsIdBefore = $p->prdsId;
                $yearBefore =  $p->prdsNameShort;
            }

            




            /*$dBusinessPeriod=DBusinessPeriod::select()
                ->with([ 'doneByMonths'=>function($query) use($dbmMonth){
                    $query->where('dbmMonth',$dbmMonth);
                },
                'doneByMonths.dDoneByMonthTasks'=>function($query3){
                    $query3->orderBy('tsksId','asc');
                } ,
                'doneByMonths.dDoneByMonthTasks.task'])
                ->with(['business'])
                ->where('prdsId',$prdsId)
                ->whereHas('doneByMonths')
                ->get();*/
                $dBusinessPeriod=DBusinessPeriod::selectRaw('d_bussines_periods.*, bussines."bussRUC", bussines."bussName", RIGHT("bussRUC",1) as "_lastDigit"')
                ->join('bussines', 'bussines.bussId', '=', 'd_bussines_periods.bussId')
                ->with([ 'doneByMonths'=>function($query) use($dbmMonth){
                    $query->where('dbmMonth',$dbmMonth);
                },
                'doneByMonths.dDoneByMonthTasks'=>function($query3){
                    $query3->orderBy('tsksId','asc');
                } ,
                'doneByMonths.dDoneByMonthTasks.task'])
                /*->with(['business'=>function($query){
                    $query->orderByRaw(' RIGHT("bussRUC",1) ASC, "bussName" asc ');
                }])*/
                ->with('business')
                ->where('prdsId',$prdsId)  
                ->whereRaw('(RIGHT("bussRUC",1)=? or -1=?) ', [$ln, $ln])
                //->has('doneByMonths')
                ->whereHas('doneByMonths', function($query) use ($dbmMonth){
                    $query->where('dbmMonth',$dbmMonth);
                })
                /*->whereHas('business', function($query) use ($ln){
                    $query->whereRaw(' (RIGHT("bussRUC",1)=? or -1=?) ',[$ln,$ln]);
                })*/
                ->orderByRaw('RIGHT("bussRUC",1) ASC, "bussName" asc ')
                ->get();


                $dBusinessPeriodBefore=DBusinessPeriod::selectRaw('d_bussines_periods.*, bussines."bussRUC", bussines."bussName", RIGHT("bussRUC",1) as "_lastDigit"')
                ->join('bussines', 'bussines.bussId', '=', 'd_bussines_periods.bussId')
                ->with([ 'doneByMonths'=>function($query) use($dbmMonthBefore){
                    $query->where('dbmMonth',$dbmMonthBefore);
                },
                'doneByMonths.dDoneByMonthTasks'=>function($query3){
                    $query3->orderBy('tsksId','asc');
                } ,
                'doneByMonths.dDoneByMonthTasks.task'])
                /*->with(['business'=>function($query){
                    $query->orderByRaw(' RIGHT("bussRUC",1) ASC, "bussName" asc ');
                }])*/
                ->with('business')
                ->where('prdsId',$prdsIdBefore)  
                ->whereRaw('(RIGHT("bussRUC",1)=? or -1=?) ', [$ln, $ln])
                //->has('doneByMonths')
                ->whereHas('doneByMonths', function($query) use ($dbmMonthBefore){
                    $query->where('dbmMonth',$dbmMonthBefore);
                })
                /*->whereHas('business', function($query) use ($ln){
                    $query->whereRaw(' (RIGHT("bussRUC",1)=? or -1=?) ',[$ln,$ln]);
                })*/
                ->orderByRaw('RIGHT("bussRUC",1) ASC, "bussName" asc ')
                ->get();
                $dBusinessPeriodBeforeArray = $dBusinessPeriodBefore->toArray();
 
                $dBusinessPeriodBeforeArrayC= array_reduce($dBusinessPeriodBeforeArray, function($array, $obj) {
                      $array['bussRUC_'.$obj['bussRUC']]=$obj; 
                      return $array;
                },[]);

                $dBusinessPeriod = $dBusinessPeriod->map(function ($item) use ($dBusinessPeriodBeforeArrayC) {
                    $item['doneByMonthsBefore'] = $dBusinessPeriodBeforeArrayC['bussRUC_'.$item['bussRUC']]['doneByMonths']??[];
                    return $item;
                });
                
        




                $dataGrouped = array();
                if($ln>=0 && $ln<=9/*Entonces solo regres de un digito */){
                    //*NO hay necesidad de agrupar por ultimo digito por que solo es uno */
                    $aux1 = $dBusinessPeriod;
                    //$aux2 = array_values($aux1);
                    $temp = array();
                    $temp['name'] = 'RUC ' . $ln;
                    $temp['values'] = $aux1;
                    /*aqui guardamos el nombre de mes para luego plasmarlo en el formato de forma mas facil */
                    $temp['month']= strtoupper($nameMonths[$dbmMonth - 1]);
                    $temp['month_before'] =  strtoupper($nameMonths[$dbmMonthBefore - 1]);
                    $temp['year'] = $year ;
                    $temp['year_before'] =  $yearBefore;

                    

                    $dataGrouped["digit-" . $ln] = $temp;
                }
                if($ln==-1){
                    //agrupa por ultimo digito
                    for ($i = 0; $i < 10; $i++) {
                        $aux1 = array_filter($dBusinessPeriod->toArray(), function ($element) use ($i) {
                            return intval($element['_lastDigit']) == intval($i);
                        });
                        $aux2 = array_values($aux1);
                        $temp = array();
                        $temp['name'] = 'RUC ' . $i;
                        $temp['values'] = $aux1;
                        /*aqui guardamos el nombre de mes para luego plasmarlo en el formato de forma mas facil */
                        $temp['month']= strtoupper($nameMonths[$dbmMonth - 1]);
                        $temp['month_before'] =  strtoupper($nameMonths[$dbmMonthBefore - 1]);
                        $temp['year'] = $year ;

                        $temp['year_before'] =  $yearBefore;

                        $dataGrouped["digit-" . $i] = $temp;
                    }
                }


            $users=User::select('id', 'perId')
                ->with('person')
                ->get();

            $data = [
                '_dBusinessPeriods' => $dBusinessPeriod,
                'groupeds' => $dataGrouped,
                'users' => $users,
                'date' => $f,
                'getUserName'=>function($users, $id){
                    $aux = array_filter($users->toArray(), function ($element) use ($id) {
                        return intval($element['id']) == intval($id);
                    });
                    $aux1=array_values($aux);
                    $user=(count($aux1) >0)?$aux1[0]:null;
                    $perName=($user!=null)?explode(" ",$user['person']['perName'])[0]:'-----';
                    return ucwords($perName);
                },
                'getBussRegimeName'=>function($bussRegime){
                    if(!$bussRegime) return "";
                    $a=array("1"=>"ESPECIAL", "2"=>"GENERAL", "3"=>"MYPE");
                    return $a[$bussRegime];
                },
                'getBussFileKindName'=>function($bussFileKind){
                    if(!$bussFileKind) return "";
                    $a=array("1"=>"Archivador", "2"=>"Folder");
                    return $a[$bussFileKind];
                },
                'getBussKindBookAccName'=>function($bussKindBookAcc){
                    if(!$bussKindBookAcc) return "";
                    $a=array("1"=>"Electronico", "2"=>"Computarizado");
                    return $a[$bussKindBookAcc];
                }



            ];

            $path = base_path('resources/views/v1.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data1 = file_get_contents($path);
            $pic = 'data:image/' . $type . ';base64,' . base64_encode($data1);

            $path1 = base_path('resources/views/icon.jpg');
            $type1 = pathinfo($path1, PATHINFO_EXTENSION);
            $data2 = file_get_contents($path1);
            $pic1 = 'data:image/' . $type1 . ';base64,' . base64_encode($data2);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->setPaper('A4', 'landscape')->loadView('reports2.reports-tasks-by-sub-period-with-before-month', compact('pic', 'pic1'), $data);

            return $pdf->stream();
        } catch (Exception $e) {
            throw $e;
            return 'Surgio un error, intente más tarde';
        }
    }

}
