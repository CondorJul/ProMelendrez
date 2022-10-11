<html>

<head>
    <style type="text/css">
        @page {
            margin: 80px 74px 74px 100px;
        }

        header {
            border-bottom: 2px solid #CC0101;
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
        }


        footer {
            border-top: 2px solid #CC0101;
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;

            color: black;
            text-align: center;
        }

        #watermark {
            position: fixed;
            right: 5%;
            bottom: 25%;
            width: 15cm;
            height: 15cm;
            z-index: -1000;
            opacity: 0.3;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="../resources/views/icon.png" height="100%" width="100%" />
    </div>
    <header>
        <div style="text-align: right; margin-bottom: 5px;">
            <img src="<?php echo $pic ?>" alt="" width="250px" height="60px">
        </div>
    </header>
    <footer>
        <div style="font-size: 10.8px; line-height: 12px; padding-top: 5px;">EL PRESENTE ES PARA INFORMAR RESPECTO A LOS HONORARIOS POR SERVICIOS QUE NOS ADEUDA HASTA LA FECHA. <br> Ante cualquier duda o consulta, sírvase comunicarse con nosotros al celular <span style="color: red; font-weight: bold;">N° 973896051 / 951415451</span> a los señores: <br> José Luis MELENDRES CÓNDOR y/o Rita Cindy MIRANDA RAMOS, o al correo <span style="color: blue; font-weight: bold;">melendres.auditores@hotmail.com</span></div>
    </footer>
    <main>
        <div style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
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
            <br><br>
            @endforeach

            <table align="center" style="width: 50%;" border="1" cellspacing="0">
                <tr>
                    <th colspan="3" style="font-size: 16px; letter-spacing: 5px;">RESUMEN GENERAL</th>

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
                    <th style="width: 50%; text-align: right; font-weight: lighter;">{{ $value['dbpDebt'] }}</th>
                </tr>
                @endforeach
                <tr>
                    <th>TOTAL</th>
                    <th style="width: 1px; border-right: 1px solid white;">&nbspS/</th>
                    <th style="width: 50%; text-align: right;">{{ number_format($total2, 2, '.', '')}}</th>
                </tr>
            </table>
        </div>
        <br><br>
        <div class="contenedor" style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
            <table style="width: 100%;" border="1" cellspacing="0">
                <tr>
                    <th>ENTIDAD BANCARIA</th>
                    <th>CUENTA SOLES</th>
                    <th>CÓDIGO INTERBANCARIO CCI</th>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center;">BBVA Banco Continental</th>
                    <th style="text-align: center; font-weight: lighter;">Cuenta de Ahorros</th>
                    <th rowspan="2" style="text-align: center;">011-321-000200838764-75</th>
                </tr>
                <tr>
                    <th style="text-align: center;">N° 0011 0321 0200838764</th>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center;">BCP Banco de credito del Perú</th>
                    <th style="text-align: center; font-weight: lighter;">Cuenta digital</th>
                    <th rowspan="2" style="text-align: center;">002-28019403687800568</th>
                </tr>
                <tr>
                    <th style="text-align: center;">N° 280-94036878-0-05</th>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center;">Banco Interbank</th>
                    <th style="text-align: center; font-weight: lighter;">Cuenta simple</th>
                    <th rowspan="2" style="text-align: center;">003-590-013116283167-74</th>
                </tr>
                <tr>
                    <th style="text-align: center;">N° 5903116283167</th>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center;">Caja Huancayo</th>
                    <th style="text-align: center; font-weight: lighter;">Cuenta de Ahorros</th>
                    <th rowspan="2" style="text-align: center;">80806621100065174655</th>
                </tr>
                <tr>
                    <th style="text-align: center;">N° 107066211000651746</th>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>
