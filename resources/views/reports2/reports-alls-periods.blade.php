<html>

<head>
    <style type="text/css">
        .a {
            border: 1px solid black;
            width: 49%;
        }

        .b {
            border: 1px solid black;
            width: 49%;
            margin-left: 5px;
        }

        .contenedor {
            margin: 0 auto;
        }

        .contenedor .a,
        .contenedor .b {
            display: inline-block;
        }

        footer {
            border-top: 1px solid black;
            position: fixed;
            bottom: -40px;

            height: 40px;

            /** Extra personal styles **/

            color: black;
            text-align: center;

        }
    </style>
</head>

<body>
    <div style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
        <div style="text-align: right;">
            <img src="<?php echo $pic ?>" alt="" width="250px">
        </div>
        <h1 style="text-align: center; font-size: 35px; font-family: Arial, Helvetica, sans-serif; text-decoration: underline; margin: 5px;">{{ $business['bussName'] }}</h1>
        <h2 style="text-align: center; font-size: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: 0px;">RUC: {{ $business['bussRUC'] }}</h2>
        <h2 style="text-align: right; font-size: 12px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px;">Pasco, {{ $date }}</h2>

        @foreach($d_business_period as $key => $value)
        <table style="width: 100%;" border="1" cellspacing="0">
            <tr>
                <th colspan="4" style="font-size: 20px; letter-spacing: 8px;">EJERCICIO {{ $value->periods['prdsNameShort'] }}</th>
            </tr>
            <tr>
                <th>MESES</th>
                <th>DESCRIPCIÓN</th>
                <th colspan="2">DEUDA</th>

            </tr>
            @foreach($value['serviceProvided'] as $key=> $val)
            <tr>
                <th style="font-weight: lighter;">{{ $val->periodPayments['ppayName'] }}</th>
                <th style="width: 45%; font-weight: lighter;">{{ $val->services['svName'] }}</th>

                <th style="width: 1px; font-weight: lighter; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 25%; font-weight: lighter; text-align: right;">{{ $val['spDebt'] }}</th>
            </tr>
            @endforeach
            <tr>
                <th colspan="2" style="width: 35%;">TOTAL</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 25%; text-align: right;">{{ $value['dbpDebt'] }}</th>

            </tr>
        </table>
        <br>
        @endforeach

        <table style="width: 100%;" border="1" cellspacing="0">
            <tr>
                <th colspan="3" style="font-size: 20px; letter-spacing: 8px;">RESUMEN GENERAL</th>

            </tr>
            <tr>
                <th>AÑOS</th>

                <th colspan="2">DEUDA</th>

            </tr>
            @php
            $total = 0;
            $total1 = 0;
            $total2 = 0;
            @endphp
            @foreach($d_business_period as $key => $value)
            @php
            $total += $value['dbpCost'];
            $total1 += $value['dbpPaid'];
            $total2 += $value['dbpDebt'];
            @endphp
            <tr>
                <th style="font-weight: lighter;">{{ $value->periods['prdsNameShort'] }}</th>
                <th style="width: 1px; border-right: 1px solid white; font-weight: lighter;">&nbspS/</th>
                <th style="width: 25%; text-align: right; font-weight: lighter;">{{ $value['dbpDebt'] }}</th>
            </tr>
            @endforeach
            <tr>
                <th>TOTAL</th>
                <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                <th style="width: 25%; text-align: right;">{{ number_format($total2, 2, '.', '')}}</th>
            </tr>
        </table>
    </div>
    <br><br> <br>
    <div class="contenedor">
        <div class="a" style="font-size: 14px; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif;">
            CUENTA DE AHORROS <br> MELENDRES AUDITORES CONSULTORES S.A.C.
            <div style="border-top: 1px solid black; padding: 8px;">
                <span>Cuenta Soles BBVA BANCO CONTINENTAL</span> <br>
                <span style="font-weight: normal;">N° 0011 0321 0200838764</span> <br><br>

                <span>Código Interbancario CCI es </span> <br>
                <span style="font-weight: normal;">011-321-000200838764-75</span>
            </div>
        </div>
        <div class="b" style="font-size: 14px; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif;">
            CUENTA DIGITAL <br> JOSE LUIS MELENDRES CONDOR
            <div style="border-top: 1px solid black; padding: 8px;">
                <span style="font-size: 12px;">Cuenta Soles BCP BANCO DE CREDITO DEL PERU</span> <br>
                <span style="font-weight: normal;">N° 280-94036878-0-05</span> <br><br>

                <span>Código Interbancario CCI es </span> <br>
                <span style="font-weight: normal;">002-28019403687800568</span>
            </div>
        </div>
    </div>
    <br>
    <div class="contenedor">
        <div class="a" style="font-size: 14px; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif;">
            CUENTA SIMPLE <br> JOSE LUIS MELENDRES CONDOR
            <div style="border-top: 1px solid black; padding: 8px;">
                <span>Cuenta Soles BANCO INTERBANK </span> <br>
                <span style="font-weight: normal;">N° 5903116283167</span> <br><br>

                <span>Código Interbancario CCI es </span> <br>
                <span style="font-weight: normal;">-</span>
            </div>
        </div>
        <div class="b" style="font-size: 14px; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif;">
            CUENTA DE AHORRROS <br> JOSE LUIS MELENDRES CONDOR
            <div style="border-top: 1px solid black; padding: 8px;">
                <span style="font-size: 12px;">Cuenta Soles CAJA HUANCAYO</span> <br>
                <span style="font-weight: normal;">N° 107066211000651746</span> <br><br>

                <span>Código Interbancario CCI es </span> <br>
                <span style="font-weight: normal;">80806621100065174655</span>
            </div>
        </div>
    </div>
</body>
<footer>
    <div style="font-size: 11px; line-height: 12px; padding-top: 5px;">EL PRESENTE ES PARA INFORMAR RESPECTO A LOS HONORARIOS POR SERVICIOS QUE NOS ADEUDA HASTA LA FECHA. <br> Ante cualquier duda o consulta, sírvase comunicarse con nosotros al celular <span style="color: red;">N° 973896051 / 951415451</span> a los señores José Luis MELENDRES CÓNDOR y/o <br> Rita Cindy MIRANDA RAMOS, o al correo <span style="color: blue;">melendres.auditores@hotmail.com</span></div>
</footer>

</html>
