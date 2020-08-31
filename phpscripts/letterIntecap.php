<?php
    $name = $_GET['name'];
    $id = $_GET['id'];
    $company = $_GET['company'];
    $hiring_date = $_GET['hiring'];
    $date = $_GET['date'];
    $afiliacion = $_GET['afiliacion'];
    $patronal = $_GET['patronal'];
    $user = $_GET['user'];
    $contact = $_GET['contact'];
    $job = $_GET['job'];
    $iduser = $_GET['iduser'];

echo "
<div style='margin-left:60px'>
<table>
<tr>
<td colspan='4'><img src='http://200.94.251.67/assets/Nearsol.png' style='height:60px; width:250px'></td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td colspan='3'></td>
<td style='text-align:right'>" . $date . "</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td colspan='4'>Señores:</td>
</tr>
<tr>
<td colspan='4'>INTECAP</td>
</tr>
<tr>
<td colspan='4'>Presente</td>
</tr>
<tr>
<td colspan='4'>Estimados presentes</td>
</tr>
<tr>
<td style=width:30px></td>
<td colspan='3'>Se hace constar que la información que se detalla acontinuación es real y fidedigna, por</td>
</tr>
<tr>
<td colspan='4'>haber sido extraida de los controles internos del Departamento de Planillas.</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>NOMBRE:</td>
<td style=width:30px;></td>
<td>" . $name . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>IDENTIFICACION:</td>
<td style=width:30px;></td>
<td>" . $id . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>AFILIACION:</td>
<td style=width:30px;></td>
<td>" . $afiliacion . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>EMPRESA:</td>
<td style=width:30px;></td>
<td>" . $company . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>NUMERO PATRONAL:</td>
<td style=width:30px;></td>
<td>" . $patronal . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>FECHA DE INGRESO:</td>
<td style=width:30px;></td>
<td>" . $hiring_date . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td></td>
<td colspan='3'>Para los usos que a la persona interesada convenga se hace constar que la</td>
</tr>
<td colspan='4'>empresa paga la contidad de Q. 28.25, por concepto de INTECAP por cada uno</td>
</td>
<tr>
<td colspan='4'>de los trabajadores</td>
</tr>
<tr>
<td colspan='4' style='text-align:center'>Atentamente,</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
</tr>
<tr>
<td colspan='4' style='height:50px'></td>
</tr>
<tr>
<td colspan='4' style='text-alignt:center'>
<table styel='width:100%'>
<tr>
<td><img style='width:100px;height:50px;margin-left:250px' src='http://200.94.251.67/uploads/" . $iduser . "_Signatures_.jpeg'></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan='4' style=' text-align: center'>" . $user . "
</tr>
<tr>
<td colspan='4' style='text-align:center'>" . $job . "</td>
</tr>
<tr>
<td colspan='4' style='height:40px; border-bottom:solid 4px'></td>
</tr>
<tr>
<td colspan='4' style='height:1px; border-bottom:solid 1px'></td>
</tr>
<tr>
<td colspan='4' style='text-align:center'>20 CALLE 05-25, ZONA 10 TORRE NEARSOL NIVEL 04</td>
</tr>
<tr>
<td colspan='4' style='text-align:center'>PBX: 2504-2700 ext." . $contact ."</td>
</tr>
</table>
"
?>