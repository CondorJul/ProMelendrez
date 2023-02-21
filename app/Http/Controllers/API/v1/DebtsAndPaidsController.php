<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtsAndPaidsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        \LogActivity::add($request->user()->email.' ha realizado una consulta de deudas y pagos.', null, json_encode($request->all()));

        $params=[];
        $queryWhereBuss=' where 1=1 ';
        $queryWhereSerPro=' where 1=1 ';

        /*ES PARA EL PRIMER SELECT */
        if($request->tellId>0){
            $queryWhereBuss.=' and bussines."tellId"=? ';
            array_push($params,$request->tellId );
        } 

        if($request->bussState>0){
            $queryWhereBuss.=' and bussines."bussState"=? ';
            array_push($params,$request->bussState );
        } 

        if(!empty($request->q)){
            $queryWhereBuss.=' and (lower("bussName" ) like lower(?) or "bussRUC" like ?) ';
            $q='%'.$request->q.'%';
            array_push($params,$q, $q);
        }

        /*RECORDAR QUE ES PARA EL SEGUNDO SELECT  */
        if($request->prdsId>0){
            $queryWhereSerPro.=' and "prdsId"=? ';
            array_push($params,$request->prdsId );
        } 

        if($request->svId>0){
            $queryWhereSerPro.=' and "svId"=? ';
            array_push($params,$request->svId );
        } 

        if($request->ppayId>0){
            $queryWhereSerPro.=' and "ppayId"=? ';
            array_push($params,$request->ppayId );
        } 

        /*
              $r=DB::select('SELECT 
                bussines."bussId", bussines."bussState", bussines."tellId", "bussName", "bussRUC", "bussTel", 
                "prdsId", 
                "svId", "ppayId", "spName", "spCost", "spDebt", "spPaid" 
            FROM bussines LEFT JOIN d_bussines_periods 
                on bussines."bussId"=d_bussines_periods."bussId" 
            LEFT JOIN services_provided 
                ON d_bussines_periods."dbpId"=services_provided."dbpId" '.$queryWhere,$params);
        
        
        
        */
        $r=DB::select('select * from 
                (select 
                    bussines."bussId", bussines."bussState", bussines."tellId", "bussName", "bussRUC", "bussTel", "bussFileNumber"
                FROM bussines '.$queryWhereBuss.' ) b LEFT JOIN
            
                (SELECT 
                        d_bussines_periods."bussId" as "_bussId",
                        "prdsId", 
                        "svId", "ppayId", "spName", "spCost", "spDebt", "spPaid" 
                    
                    FROM d_bussines_periods 
                    LEFT JOIN services_provided 
                        ON d_bussines_periods."dbpId"=services_provided."dbpId" '.$queryWhereSerPro.') sp 
            ON   b."bussId"=sp."_bussId" ORDER BY RIGHT("bussRUC",1) ASC, "bussRUC" ASC,  "prdsId" ASC NULLS LAST, "svId" ASC NULLS LAST, "ppayId" ASC NULLS LAST',$params);
        
            return response()->json([
            'res'=>true,
            'msg'=>'Listado correctamente ',
            'data'=>$r//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=1 '.$queryWhere.' order by "elapsedSeconds" DESC',$params)
        ],200);
    }


    public function getLastPaymentByClient(Request $request){
        \LogActivity::add($request->user()->email.' ha realizado una consulta de último pagos por cliente.', null, json_encode($request->all()));

        $params=[];
        $queryWhereBuss=' where 1=1 ';

        /*ES PARA EL PRIMER SELECT */
        if($request->tellId>0){
            $queryWhereBuss.=' and bussines."tellId"=? ';
            array_push($params,$request->tellId );
        } 


        
        $r=DB::select('
        select * from
(select 
        	bussines."bussId", bussines."bussState", bussines."tellId", "bussName", "bussRUC", "bussTel", "bussFileNumber", "bussStateDate"
	FROM bussines '.$queryWhereBuss.') b 
    LEFT JOIN (
SELECT distinct on (d_bussines_periods."bussId")  
        payments."payId", "payToken", "paySerie",   "payNumber","payDatePrint", 
      d_bussines_periods."bussId" as "_bussId",  d_bussines_periods."prdsId", 
      "spName","svId", "ppayId",  "spCost", "spDebt", "spPaid"  FROM payments 
      
INNER JOIN payment_details ON payments."payId"= payment_details."payId" 
INNER JOIN services_provided ON payment_details."spId"=services_provided."spId"
INNER JOIN d_bussines_periods on services_provided."dbpId"=d_bussines_periods."dbpId"
INNER JOIN periods ON d_bussines_periods."prdsId"=periods."prdsId"
where "payState"=3 AND  "payIsCanceled"=2 and services_provided."svId"=1 ORDER BY d_bussines_periods."bussId", periods."prdsNameShort" DESC, services_provided."svId" ASC, services_provided."ppayId" DESC) lp ON b."bussId" = lp."_bussId" 

LEFT JOIN (select bussines."bussId" as "_bussId", 
	sum("spCost") as "sumSpCost", sum("spDebt") as "sumSpDebt",sum("spPaid") as 	"sumSpPaid",
    /*Sumar cantidades superiores a 0*/
    sum(
      CASE WHEN "spDebt">0 THEN 1
           ELSE  0
       END
    ) AS "countSpDebt", 
    sum(
      CASE WHEN "spPaid">0 THEN 1
           ELSE  0
       END
    ) AS "countSpPaid", 
    count(*) as "countTotal"

from bussines 
	INNER JOIN d_bussines_periods on bussines."bussId"=d_bussines_periods."bussId"
	INNER JOIN services_provided on d_bussines_periods."dbpId"=services_provided."dbpId"
    where "svId"=1
    
    GROUP BY "_bussId" ) pd on lp."_bussId"=pd."_bussId"





ORDER BY RIGHT("bussRUC",1) ASC;
        ', $params);
return response()->json([
    'res'=>true,
    'msg'=>'Listado correctamente ',
    'data'=>$r//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=1 '.$queryWhere.' order by "elapsedSeconds" DESC',$params)
],200);

    }


    public function getOldDebtByClient(Request $request){
        \LogActivity::add($request->user()->email.' ha realizado una consulta de debe desde tal periodo  y mes por cliente.', null, json_encode($request->all()));

        $params=[];
        $queryWhereBuss=' where 1=1 ';

        /*ES PARA EL PRIMER SELECT */
        if($request->tellId>0){
            $queryWhereBuss.=' and bussines."tellId"=? ';
            array_push($params,$request->tellId );
        } 


        
        $r=DB::select('

        select *        from
(select 
        	bussines."bussId", bussines."bussState", bussines."tellId", "bussName", "bussRUC", "bussTel", "bussFileNumber", "bussStateDate"
	FROM bussines '.$queryWhereBuss.') b 
    LEFT JOIN (
      
SELECT distinct on (d_bussines_periods."bussId")  
    

      d_bussines_periods."bussId" as "_bussId",  d_bussines_periods."prdsId", 
      "spName","svId", "ppayId",  "spCost", "spDebt", "spPaid" 
      
      
from services_provided 
INNER JOIN d_bussines_periods on services_provided."dbpId"=d_bussines_periods."dbpId"
INNER JOIN periods ON d_bussines_periods."prdsId"=periods."prdsId"
where services_provided."svId"=1 and services_provided."spDebt"<>0 
ORDER BY d_bussines_periods."bussId", periods."prdsNameShort" ASC, 
services_provided."svId" ASC, 
services_provided."ppayId" ASC

) lp ON b."bussId" = lp."_bussId" 

LEFT JOIN (select bussines."bussId" as "_bussId", 
	sum("spCost") as "sumSpCost", sum("spDebt") as "sumSpDebt",sum("spPaid") as 	"sumSpPaid",
    /*Sumar cantidades superiores a 0*/
    sum(
      CASE WHEN "spDebt">0 THEN 1
           ELSE  0
       END
    ) AS "countSpDebt", 
    sum(
      CASE WHEN "spPaid">0 THEN 1
           ELSE  0
       END
    ) AS "countSpPaid", 
    count(*) as "countTotal"

from bussines 
	INNER JOIN d_bussines_periods on bussines."bussId"=d_bussines_periods."bussId"
	INNER JOIN services_provided on d_bussines_periods."dbpId"=services_provided."dbpId"
   /* where "svId"=1*/
    
    GROUP BY "_bussId" ) pd on lp."_bussId"=pd."_bussId"


ORDER BY RIGHT("bussRUC",1) ASC;

 ', $params);
return response()->json([
    'res'=>true,
    'msg'=>'Listado correctamente ',
    'data'=>$r//DB::select('select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=1 '.$queryWhere.' order by "elapsedSeconds" DESC',$params)
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


    /*
    select * from (select "tellId", "tellName", "userId" from teller) te LEFT JOIN
(select count(*) as total, created_by from  audits GROUP BY created_by order by total DESC)   co ON te."userId"=co.created_by
    
    */




}


/*

 select *        from
(select 
        	bussines."bussId", bussines."bussState", bussines."tellId", "bussName", "bussRUC", "bussTel", "bussFileNumber"
	FROM bussines '.$queryWhereBuss.') b 
    LEFT JOIN (
      
SELECT distinct on (d_bussines_periods."bussId")  
    

      d_bussines_periods."bussId" as "_bussId",  d_bussines_periods."prdsId", 
      "spName","svId", "ppayId",  "spCost", "spDebt", "spPaid" ,
      
      count(*) OVER() AS total_count
      
from services_provided 
INNER JOIN d_bussines_periods on services_provided."dbpId"=d_bussines_periods."dbpId"
INNER JOIN periods ON d_bussines_periods."prdsId"=periods."prdsId"
where services_provided."svId"=1 and services_provided."spDebt"<>0 
ORDER BY d_bussines_periods."bussId", periods."prdsNameShort" ASC, 
services_provided."svId" ASC, 
services_provided."ppayId" ASC

) lp ON b."bussId" = lp."_bussId" ORDER BY RIGHT("bussRUC",1) ASC;


*/


/*select *        from
(select 
        	bussines."bussId", bussines."bussState", bussines."tellId", "bussName", "bussRUC", "bussTel", "bussFileNumber"
	FROM bussines ) b 
    LEFT JOIN (
      
SELECT distinct on (d_bussines_periods."bussId")  
    

      d_bussines_periods."bussId" as "_bussId",  d_bussines_periods."prdsId", 
      "spName","svId", "ppayId",  "spCost", "spDebt", "spPaid" 
      
      
from services_provided 
INNER JOIN d_bussines_periods on services_provided."dbpId"=d_bussines_periods."dbpId"
INNER JOIN periods ON d_bussines_periods."prdsId"=periods."prdsId"
where services_provided."svId"=1 and services_provided."spDebt"<>0 
ORDER BY d_bussines_periods."bussId", periods."prdsNameShort" ASC, 
services_provided."svId" ASC, 
services_provided."ppayId" ASC

) lp ON b."bussId" = lp."_bussId" ORDER BY RIGHT("bussRUC",1) ASC; */