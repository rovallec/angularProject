<?php
$address = $_GET['address'];
$afiliacion = $_GET['afiliacion'];
$birthday_day = $_GET['birthday_day'];
$birthday_month = $_GET['birthday_month'];
$birthday_year = $_GET['birthday_year'];
$book = $_GET['book'];
$cedula = $_GET['cedula'];
$company = $_GET['company'];
$conyuge_firstname = $_GET['conyuge_firstname'];
$conyuge_lastname = $_GET['conyuge_lastname'];
$department = $_GET['department'];
$dpi = $_GET['dpi'];
$extended = $_GET['extended'];
$f = $_GET['f'];
$first_lastname = $_GET['first_lastname'];
$first_name = $_GET['first_name'];
$fold = $_GET['fold'];
$m = $_GET['m'];
$married = $_GET['married'];
$municipio = $_GET['municipio'];
$partida = $_GET['partida'];
$pasaport = $_GET['pasaport'];
$patronal = $_GET['patronal'];
$phone = $_GET['phone'];
$reg = $_GET['reg'];
$second_lastname = $_GET['second_lastname'];
$second_name = $_GET['second_name'];
$zone = $_GET['zone'];
$email = $_GET['email'];

echo("
<div style='margin-left:50px; width:900px'>
<table style='width:100%>
<tr>
    <table style='width:100%'>
        <tr>
            <td style='border-bottom:solid 3px black;'>
                <table style='width:100%'>
                    <tr>
                        <td><img src='http://200.94.251.67/assets/irtra.png' style='height:50px; width:120px'>
                        </td>
                        <td>
                            <table style='width:100%'>
                                <tr>
                                    <td style='text-align: center'>INSTITUTO DE RECREACION DE LOS TRABAJADORES
                                    </td>
                                </tr>
                                <tr>
                                    <td style='text-align: center'>DE LA EMPRESA PRIVADA DE GUATEMALA</td>
                                </tr>
                                <tr>
                                    <td style='text-align: center'>7ma. Avenida 7-24, Zona 9, Ciudad de
                                        Guatemala</td>
                                </tr>
                                <tr>
                                    <td style='text-align: center'>PBX:2423-9000 / 2423-9100</td>
                                </tr>
                                <tr>
                                    <td style='font-size:10px'>e-mail: afiliaciones@irtra.org.gt</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style='width:100%'>
                                <tr style='height:50px'>
                                    <td style='text-align:center;border:solid 2px black'><b style='color:#e30712; font-size:16px'>AFI-02</b></td>
                                </tr>
                                <tr style='height:40px'></tr>
                                <tr>
                                    <td><p style='color:#e50712; font-size:10px'>LLENAR EN COMPUTADORA – NO LLENAR A MANO<p></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:25px'></tr>
        <tr>
            <td colspan='3'>Señor Afiliado:</td>
        </tr>
        <tr style='height:10px'></tr>
        <tr>
            <td colspan='3' style='  text-align: justify;text-justify: inter-word;'>EN VIRTUD DE ESTAR SISTEMATIZANDO NUESTROS REGISTROS SOBRE PATRONOS Y TRABAJADORES MUCHO LE AGRADECEMOS SE SIRVA PROPORCIONARNOS LA INFORMACION SOLICITADA, LA CUAL SERÁ DE MUCHA IMPORTANCIA PARA PODER BRINDARLE UN MEJOR SERVICIO. *SI DESEA QUE SU ESPOSO (A) PUEDA INGRESAR A NUESTROS PARQUES CON SU CARNET, ANOTELO EN ESTA BOLETA.</td>
        </tr>
        <tr style='height:15px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Nombre Empresa:<td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center;'>$company<td>
                        <td>No. Patronal IGSS:<td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$patronal<td>
                        <td>No. Afiliacion IGSS:<td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$afiliacion<td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Nombre del Trabajador: Primer Nombre:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$first_name</td>
                        <td>Segundo Nombre:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$second_name</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Primer Apellido:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$first_lastname</td>
                        <td>Segundo Apellido:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$second_lastname</td>
                        <td>De casada:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$married</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Sexo:</td>
                        <td>M</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$m</td>
                        <td>F</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$f</td>
                        <td>DPI:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$dpi</td>
                        <td>Departamento:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>Guatemala</td>
                        <td>Municipio:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>Guatemala</td>
                    </tr>
                </table>
            <td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Lugar y Fecha de Nacimiento:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>Guatemala</td>
                        <td>dia:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$birthday_day</td>
                        <td>mes:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$birthday_month</td>
                        <td>Año:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$birthday_year</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>(Menores de Edad):Libro No.:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$book</td>
                        <td>Folio:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$fold</td>
                        <td>Partida:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$partida</td>
                        <td>Pasaporte:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$pasaport</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Dirección:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:600px;text-align:center'>$address</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Departamento:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$department</td>
                        <td>Municipio:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$municipio</td>
                        <td>Zona:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$zone</td>
                        <td>Teléfono:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$phone</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Nombre del Cónyuge:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$conyuge_firstname</td>
                        <td>Apellidos del Cónyuge:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:70px;text-align:center'>$conyuge_lastname</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:2.5px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr>
                        <td>Proporcione su correo electronico:</td>
                        <td style='border-bottom:solid 1.5px black;min-width:600px;text-align:center'>$email</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style='height:25px'></tr>
        <tr>
            <td colspan='3'>
                <table style='width:100%'>
                    <tr style='height:153'>
                    <td style='width:100'></td>
                        <td>
                            <table style='width:100%'>
                                <tr style='height:96px'>
                                    <td style='border:solid 2px black;width:210px'></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td style='font-size:10px; text-align:center'><u>(FIRMA)</u></td>
                                            </tr>
                                            <tr>
                                                <td style='font-size:9px; text-align:center'><u>FAVOR FIRMAR CON <b>TINTA NEGRA</b> SIN</u></td>
                                            </tr>
                                            <tr>
                                                <td style='font-size:9px; text-align:center'><u>SALIRSE DEL MARCO DE REFERENCIA</td>
                                            </tr>
                                        </table>
                                    </td>
                                <tr>
                            </table>
                        </td>
                        <td style='width:320'></td>
                        <td style='width:152px;heihgt:153px;border:solid 2px black'></td>
                        <td style='width:100'></td>
                    </tr>       
                <table>
            </td>
        </tr>
    </table>
</div>
");
?>