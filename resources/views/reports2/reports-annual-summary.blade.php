<html>

<head>
    <style type="text/css">
        @page {
            margin: 80px 74px 74px 100px;
        }

        header {
            border-bottom: 2px solid #CC0101;
            position: fixed;
            top: -74px;
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
            right: 20%;
            bottom: 5%;
            width: 15cm;
            height: 15cm;
            z-index: -1000;
            opacity: 0.3;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .a {
            /*border: 2px solid black;*/
            width: 49%;
        }

        .b {
            /*border: 2px solid black;*/
            width: 50%;
            margin-left: 3px;
        }

        .contenedor {
            margin: 0 auto;
        }

        .contenedor .a,
        .contenedor .b {
            display: inline-block;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="<?php echo $pic1 ?>" height="100%" width="100%" />
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
            <h1 style="text-align: center; font-size: 25px; font-family: Arial, Helvetica, sans-serif; text-decoration: underline; margin: 5px;">{{ $business['bussName'] }}</h1>
            <h2 style="text-align: center; font-size: 25px; font-family: Arial, Helvetica, sans-serif; margin-top: 0px;">RUC: {{ $business['bussRUC'] }}</h2>
            <h2 style="text-align: right; font-size: 12px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px;">Pasco, {{ $date }}</h2>
            <h2 style="text-align: left; font-size: 14px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px;">RESPONSABLE: <span style="font-family: Arial, Helvetica, sans-serif; font-weight: lighter;">{{ $business->person['perName'] }}</span></h2>
            <h2 style="text-align: left; font-size: 14px; font-family: Arial, Helvetica, sans-serif; margin-top: 5px;">CONTACTO: <span style="font-family: Arial, Helvetica, sans-serif; font-weight: lighter;">{{ $business['bussTel'] }} - {{ $business['bussTel2'] }} - {{ $business['bussTel3'] }}</span></h2>
        </div>
        <br>
        <div class="contenedor" style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
            <div class="a">
                <table style="width: 100%;" border="1" cellspacing="0">
                    <tr>
                        <th colspan="6" style="font-size: 20px;">EJERCICIO 2020</th>
                    </tr>
                    <tr>
                        <th rowspan="2">MESES</th>
                        <th colspan="3">VENTAS</th>
                        <th rowspan="2">PLAME</th>
                        <th rowspan="2">HONORARIOS</th>
                    </tr>
                    <tr>
                        <th>BASE IMPONIBLE</th>
                        <th>I.G.V</th>
                        <th>TOTAL</th>
                    </tr>

                    <tr>
                        <th style="font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                        <th style="width: 10%; font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                    </tr>
                </table>
            </div>
            <div class="b">
                <table style="width: 100%;" border="1" cellspacing="0">
                    <tr>
                        <th colspan="6" style="font-size: 20px;">EJERCICIO 2020</th>
                    </tr>
                    <tr>
                        <th rowspan="2">MESES</th>
                        <th colspan="3">VENTAS</th>
                        <th rowspan="2">PLAME</th>
                        <th rowspan="2">HONORARIOS</th>
                    </tr>
                    <tr>
                        <th>BASE IMPONIBLE</th>
                        <th>I.G.V</th>
                        <th>TOTAL</th>
                    </tr>

                    <tr>
                        <th style="font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                        <th style="width: 10%; font-weight: lighter;">-</th>
                        <th style="width: 15%; font-weight: lighter;">-</th>
                    </tr>
                </table>
            </div>
        </div>
        <div>
            <img style="margin-top: 30px;" src="https://quickchart.io/chart?c={type:'line',data:{labels:['January','February','March','April','May'],datasets:[{label:'Dogs',data:[50,60,70,180,190],fill:false,borderColor:'blue'},{label:'Cats',data:[100,200,300,400,500],fill:false,borderColor:'green'}]}}" width="100%">
        </div>
    </main>
</body>

</html>
