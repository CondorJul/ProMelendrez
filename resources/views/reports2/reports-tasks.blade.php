<html>

<head>
    <style type="text/css">
        @page {
            margin: 80px 74px 74px 80px;
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

        .espacio {
            height: 30px;
        }

        .espacio2 {
            height: 10px;
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
        <div style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;">
            <table style="width: 100%;" border="1" cellspacing="0">
                <tr>
                    <th colspan="4">RUC 4</th>
                    <th colspan="4"></th>
                    <th colspan="3">ENERO</th>
                    <th colspan="3">FEBRERO</th>
                    <th colspan="3">MARZO</th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>RAZÓN SOCIAL / EMPRESA</th>
                    <th>RUC</th>
                    <th>ALM.</th>
                    <th>ARC</th>
                    <th>REG</th>
                    <th>FECHA</th>
                    <th>LIB.</th>
                    <th>PDT.</th>
                    <th>PLM.</th>
                    <th>LIB</th>
                    <th>PDT.</th>
                    <th>PLM.</th>
                    <th>LIB</th>
                    <th>PDT.</th>
                    <th>PLM.</th>
                    <th>LIB</th>
                </tr>
                <tr>
                    <th style="width: 2%; font-weight: lighter; text-align: center;">01</th>
                    <th style="width: 23%; font-weight: lighter; text-align: center;">VILLA MINERA SAC</th>
                    <th style="width: 7%; font-weight: lighter; text-align: center;">10724563724</th>
                    <th>s</th>
                    <th style="width: 3%; font-weight: lighter; text-align: center;">999</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                    <th>s</th>
                </tr>
            </table>
        </div>

    </main>
</body>

</html>
