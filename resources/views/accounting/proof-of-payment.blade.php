<html>

<head>
    <style type="text/css">
        * {
            /*background-color: #fe0000;*/
            width: 270px;
            margin: 0px;
            padding: 0px;
        }

        table,
        tr,
        th,
        td {
            /*border: 1px solid black;*/
            border-collapse: collapse;
            background-color: white;
        }

        /*h5,
        h2 {
            margin: 0px;
            padding: 0px;
        }

        h4 {
            margin-top: 2px;
            margin-bottom: 0px;
        }

        th {
            padding: 0px;
        }*/
        .t1,
        .t2 {
            padding-top: 3px;
            padding-bottom: 3px;
        }
    </style>
</head>

<body>
    <div style="text-align: center; padding: 15px;">
        <img src="<?php echo $pic ?>" alt="" width="270px">
        <h2 style="font-size: 14px; font-family: Arial, Helvetica, sans-serif; margin-top: 8px;">RUC: 20542566630</h2>
        <h5 style="font-size: 11px; font-family: Arial, Helvetica, sans-serif; margin-top: 8px; font-weight: lighter;">Av. Daniel Alcides Carrión N° 204(Segundo Piso) <br> Urb. San Juan-Yanacancha-Pasco <br> <span style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;">E-mail: melendres.auditores@hotmail.com<br>Cel: 999918316 / 999918376 / 999918498</span></h5>
        <table style="margin: 5 0 5 0;">
            <tr>
                <th style="width: 270px; font-size: 2px; border-top: 1px dashed;"></th>
            </tr>
        </table>
        <h6 style="font-size: 18px; font-family: Arial, Helvetica, sans-serif;">RECIBO<br>{{$payment['paySerie']}}-{{sprintf('%08d', $payment['payNumber'])}}</h6>
        <table style="margin: 5 0 5 0;">
            <tr>
                <th style="width: 270px; font-size: 2px; border-top: 1px dashed;"></th>
            </tr>
        </table>
        <table class="default" style="width: 270px;">
            <tr class="t1">
                <th class="t2" style="width:50px; font-size: 12px; text-align: left; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">Señor(es):</th>
                <th class="t2" style="width:200px; font-size: 12px; text-align: left; font-weight: lighter; font-family: Arial, Helvetica, sans-serif;">{{$payment['payClientName']}}</th>
            </tr>
            <tr class="t1">
                <th class="t2" style="width:50px; font-size: 12px; text-align: left; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">R.U.C:</th>
                <th class="t2" style="width:200px; font-size: 12px; text-align: left; font-weight: lighter; font-family: Arial, Helvetica, sans-serif;">{{$payment['payClientRucOrDni']}}</th>
            </tr>
            <tr class="t1">
                <th class="t2" style="width:50px; font-size: 12px; text-align: left; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">Fecha:</th>
                <th class="t2" style="width:200px; font-size: 12px; text-align: left; font-weight: lighter; font-family: Arial, Helvetica, sans-serif;"> {{date_format( date_create ($payment['payDatePrint']),"d/m/Y h:i A")}}</th>
            </tr>
            <tr class="t1">
                <th class="t2" style="width:50px; font-size: 12px; text-align: left; font-weight: bold; font-family: Arial, Helvetica, sans-serif; padding-bottom: 8px;">Dirección:</th>
                <th class="t2" style="width:200px; font-size: 10px; text-align: left; font-weight: lighter; font-family: Arial, Helvetica, sans-serif; padding-bottom: 8px;">{{$payment['payClientAddress']}}</th>
            </tr>
        </table>
        <table class="default" style="width: 270px;">
            <tr>
                <th colspan="6" style="width: 270px; font-size: 2px; border-top: 1px dashed; padding-top: 5px;"></th>
            </tr>
            <tr>
                <th style="width: 20px; font-size: 10px; text-align: center; border-right: 1px dashed; padding-bottom: 5px;">Cant</th>
                <th style="width: 140px; font-size: 10px; text-align: center; border-right: 1px dashed; padding-bottom: 5px;">Descripción</th>
                <th colspan="2" style="width: 33px; font-size: 10px; text-align: center; border-right: 1px dashed; padding-bottom: 5px;">P. Unit</th>
                <th colspan="2" style="width: 33px; font-size: 10px; text-align: center; padding-bottom: 5px;">Importe</th>
            </tr>
            <tr>
                <th colspan="6" style="width: 270px; font-size: 2px; border-top: 1px dashed; padding-top: 5px;"></th>
            </tr>
            @foreach ($payment['paymentDetails'] as $key => $value)

            <tr>
                <th style="width: 20px; font-size: 10px; text-align: center; font-weight: lighter; border-right: 1px dashed; padding-bottom: 5px;">{{$value['pdsQuantity']}}</th>
                <th style="width: 140px; font-size: 10px; text-align: left; font-weight: lighter; border-right: 1px dashed; padding-bottom: 5px; padding-left: 2px;">{{$value['pdsDescription']}}</th>
                <th style="width: 4px; font-size: 10px; text-align: center; padding-bottom: 5px;">&nbspS/</th>
                <th style="width: 28px; font-size: 10px; text-align: right; font-weight: lighter; border-right: 1px dashed; padding-bottom: 5px;">{{$value['pdsUnitPrice']}}</th>
                <th style="width: 4px; font-size: 10px; text-align: center; padding-bottom: 5px;">&nbspS/</th>
                <th style="width: 28px; font-size: 10px; text-align: right; font-weight: lighter; padding-bottom: 5px;">{{$value['pdsAmount']}}</th>
            </tr>
            @endforeach
            <tr>
                <th colspan="6" style="width: 270px; font-size: 2px; border-top: 1px dashed; padding-top: 5px;"></th>
            </tr>
            <!--<table style="margin: 5 0 5 0;">
                <tr>
                    <th style="width: 270px; font-size: 2px; border-top: 1px dashed;"></th>
                </tr>
            </table>-->

            <tr>
                <th colspan="4" style="width:35px; font-size: 10px; text-align: right; font-weight: bold; border-right: 1px dashed; padding-bottom: 5px;">Total a Pagar&nbsp</th>
                <th style="width: 4px;font-size: 10px; text-align: center; padding-bottom: 5px;">&nbspS/</th>
                <th style="width:40px; font-size: 10px; text-align: right; font-weight: bold; padding-bottom: 5px;">{{$payment['payTotal']}}</th>
            </tr>
        </table>
        <table style="margin: 5 0 5 0;">
            <tr>
                <th style="width: 270px; font-size: 2px; border-top: 1px dashed;"></th>
            </tr>
        </table>
        <table class="default" style="width: 270px;">
            <tr>
                <th style="width:30px; font-size: 11px; text-align: left; font-weight: lighter;"><b>Son:</b></th>
                <th colspan="3" style="width:30px; font-size: 11px; text-align: left; font-weight: lighter;">{{$payment['payTotalInWords']}}</th>
            </tr>
        </table>
        <table style="margin: 5 0 5 0;">
            <tr>
                <th style="width: 270px; font-size: 2px; border-top: 1px dashed;"></th>
            </tr>
        </table>

        <!--Forma de pago-->
        <table class="default" style="width: 270px;">
            <tr>
                <th colspan="3" style="width: 100px; font-size: 11px; text-align: left; font-weight: bold;">Pagos:</th>
            </tr>
            @foreach ($payment['dPaymentPaymentMethods'] as $key => $value)

            <tr>
                <th style="width: 100px; font-size: 11px; text-align: left; font-weight: lighter;">{{$value->paymentMethod['paymthdsName']}}</th>
                <th style="width: 30px; font-size: 11px; text-align: right; font-weight: lighter;">S/</th>
                <th style="width: 30px; font-size: 11px; text-align: right; font-weight: lighter;">{{$value->dppmAmount}}</th>
            </tr>
            @endforeach

            <!--@if($payment['payTicketSN'])
            <tr>
                <th style="width: 100px; font-size: 11px; text-align: left; font-weight: lighter;">Canjeado por boleta de venta número </th>
                <th colspan="2" style="width: 30px; font-size: 11px; text-align: right; font-weight: lighter;">{{$payment['payTicketSN']}}</th>
            </tr>
            @endif
            @if($payment['payInvoiceSN'])
            <tr>
                <th style="width: 100px; font-size: 11px; text-align: left; font-weight: lighter;">Canjeado por Factura número </th>
                <th colspan="2" style="width: 30px; font-size: 11px; text-align: right; font-weight: lighter;">{{$payment['payInvoiceSN']}}</th>
            </tr>
            @endif-->
        </table>
        <!--fin de forma de pago -->

        <table style="margin: 5 0 5 0;">
            <tr>
                <th style="width: 270px; font-size: 2px; border-top: 1px dashed;"></th>
            </tr>
        </table>

        <table class="default" style="width: 270px;">
            <tr>
                <th style="width:30px; font-size: 11px; text-align: center; font-weight: bold;">Sede</th>
                <th style="width:35px; font-size: 11px; text-align: center; font-weight: bold;">Ventanilla</th>
                <th style="width:30px;font-size: 11px; text-align: center; font-weight: bold;">Usuario</th>
            </tr>
            <tr>
                <th style="width:30px; font-size: 11px; text-align: center; font-weight: lighter;">{{$headquarter['hqName']}}</th>
                <th style="width:30px; font-size: 11px; text-align: center; font-weight: lighter;">{{$teller['tellCode']}}</th>
                <th style="width:30px;font-size: 11px; text-align: center; font-weight: lighter;">{{ strtok( $user->person['perName'], " ")}}</th>
            </tr>
        </table>

        <table style="margin: 5 0 5 0;">
            <tr>
                <th style="width: 270px; font-size: 2px; border-top: 1px dashed;"></th>
            </tr>
        </table>
        <br>
        <!--<div style="font-size: 10px; text-align: center; font-weight: lighter;"><br>..............................................<br>Melendres Auditores</div>-->
        @if($payment['payTicketSN'])
        <div style="margin-top: 5px; font-size: 11px; text-align: center;">Canjeado por Boleta de Venta <br>{{$payment['payTicketSN']}}<br>¡Gracias por su preferencia...! </div>
        @endif
        @if($payment['payInvoiceSN'])
        <div style="margin-top: 5px; font-size: 11px; text-align: center;">Canjeado por Factura <br>{{$payment['payInvoiceSN']}}<br>¡Gracias por su preferencia...! </div>
        @endif
        @if(!($payment['payInvoiceSN'] || $payment['payTicketSN']))
        <div style="margin-top: 5px; font-size: 11px; text-align: center;">Canjear por Factura o Boleta de Venta<br>¡Gracias por su preferencia...! </div>
        @endif

    </div>
</body>

</html>

<!--<html>

<head>
    <style type="text/css">
        * {
            /*background-color: #fe0000;*/
            /*width: 300px;*/
            margin: 0px;
            padding: 0px;
            text-align: center;
            margin-top: 4px;
        }

        table,
        tr,
        th,
        td {
            /*border: 1px solid black;*/
            border-collapse: collapse;
            background-color: white;
            border-radius: 3px;
        }

        /*h5,
        h2 {
            margin: 0px;
            padding: 0px;
        }

        h4 {
            margin-top: 2px;
            margin-bottom: 0px;
        }

        th {
            padding: 0px;
        }*/
    </style>
</head>

<body>
    <div style="width: 270px; background-color: #fe0000; padding: 5px; margin-left: 10px;">
        <table class="default" style="width: 270px; border-radius: 3px 3px 3px 3px;">
            <tr>
                <th rowspan="2" style="width: 156px; font-size: 10px;"><img style="border-radius: 3px 3px 3px 0px;" src="<?php echo $pic ?>" alt="" width="156px"></th>
                <th style="width: 60px; font-size: 11px; background-color: white; border-radius: 3px 3px 0px 0px;border-left: 1px solid #fe0000;">20456392815</th>
            </tr>
            <tr>
                <th style="width: 60px; font-size: 11px; background-color: white; border-radius: 0px 0px 0px 3px; border-top: 1px solid #fe0000; border-left: 1px solid #fe0000;">001-000323</th>
            </tr>
        </table>
        <h5 style="font-size: 7px; font-family: Helvetica; margin-top: 2px; margin-bottom: 5px; background-color: white; border-radius: 0px 0px 3px 3px; font-style: italic; font-weight: lighter;">Av. Daniel Alcides Carrion N° 204(Segundo Nivel) - Urb. San Juan - Yanacancha - Pasco <br> Cel: 973896051 / E-mail: melendres.auditores@hotmail.com</h5>
        <table class="default" style="width: 270px;">
            <tr>
                <th style="width:50px; font-size: 11px; text-align: left; border-bottom: 1px solid #fe0000; font-weight: lighter;">Señor(es):</th>
                <th colspan="3" style="width:220px; font-size: 11px; text-align: left; border-bottom: 1px solid #fe0000; font-style: italic;">Ricardo Solis Almerco</th>
            </tr>
            <tr style="border-bottom: 1px solid #fe0000;">
                <th style="width:50px; font-size: 11px; text-align: left; font-weight: lighter;">R.U.C:</th>
                <th style="width:125px; font-size: 11px; text-align: left; font-style: italic;">10724563924</th>
                <th style="width:40px; font-size: 11px; text-align: left; font-weight: lighter;">Fecha:</th>
                <th style="width:50px;font-size: 11px; text-align: left; font-style: italic;">29/05/2022</th>
            </tr>
            <tr>
                <th style="width:50px; font-size: 11px; text-align: left; font-weight: lighter;">Direccion:</th>
                <th style="width:125px; font-size: 11px; text-align: left; font-style: italic;">Av. Jose Martin Columna Pasco</th>
                <th style="width:40px; font-size: 11px; text-align: left; font-weight: lighter;">Celular:</th>
                <th style="width:50px;font-size: 11px; text-align: left; font-style: italic;">923313696</th>
            </tr>
        </table>
        <table class="default" style="width: 270px;">
            <tr>
                <th style="width:30px; font-size: 11px; text-align: center; border-right: 1px solid #fe0000;">Cant.</th>
                <th style="width:155px; font-size: 11px; text-align: center; border-right: 1px solid #fe0000;">Descripcion</th>
                <th style="width:35px; font-size: 11px; text-align: center; border-right: 1px solid #fe0000;">P. Unit.</th>
                <th style="width:30px;font-size: 11px; text-align: center;">Importe</th>
            </tr>
            <tr>
                <th style="width:30px; font-size: 11px; text-align: center; border-right: 1px solid #fe0000; font-style: italic; font-weight: lighter;">1</th>
                <th style="width:155px; font-size: 11px; text-align: left; border-right: 1px solid #fe0000; font-style: italic; font-weight: lighter;">Por Declaracion Jurada Anual -2021</th>
                <th style="width:35px; font-size: 11px; text-align: center; border-right: 1px solid #fe0000; font-style: italic; font-weight: lighter;">50.00</th>
                <th style="width:30px;font-size: 11px; text-align: right; font-style: italic; font-weight: lighter;">2000.00</th>
            </tr>
        </table>
        <table class="default" style="width: 270px;">
            <tr>
                <th style="height: 40px; width:100px; font-size: 11px; text-align: center; border-right: 1px solid #fe0000;">Canjear por Factura o Boleta de Venta</th>
                <th style="width:75px; font-size: 9px; text-align: center; border-right: 1px solid #fe0000; font-weight: lighter;"><br>.........................................<br>Melendres Auditores</th>
                <th style="width:40px; font-size: 11px; text-align: center;">Total</th>
                <th style="font-size: 11px; text-align: right;">2000.00</th>
            </tr>
        </table>

    </div>
</body>

</html>-->
