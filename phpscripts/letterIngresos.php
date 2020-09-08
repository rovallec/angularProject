<?php
$name = $_GET['name'];
$position = $_GET['position'];
$department = $_GET['department'];
$hire = $_GET['hire'];
$base = $_GET['base'];
$productivity = $_GET['productivity'];
$total = $_GET['total'];
$date = $_GET['date'];
$user = $_GET['user'];
$job = $_GET['job'];
$contact = $_GET['contact'];
$iduser = $_GET['iduser'];
$f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
$t = explode(";", $hire);
$hire = $t[0] . " " . $f->format($t[1]);

echo "
<div style='margin-left:50px;'>
<table>
<tr>
<td colspan='4'><img src='http://200.94.251.67/assets/Nearsol.png' style='height:60px; width:250px'></td>
</tr>
<tr>
<td colspan='4' style='height:30px'></td>
</tr>
<tr>
<td colspan='4' style='text-align:center; text-decoration:underline'>CONSTANCIA LABORAL</td>
</tr>
<tr>
<td colspan='4' style='height:30px'></td>
</tr>
<tr>
<td colspan='4' style='font-size:13px'>A quien interese:</td>
</tr>
<tr>
<td style=width:30px;></td>
<td colspan='3' style='font-size:13px'>Se hace constar que la información que se detalla acontinuación es real y Fidedigna, por haber sido extraida</td>
</tr>
<tr>
<td colspan='4' style='font-size:13px'>de los controles internos del Departamento de Recursos Humanos de la entidad PRG Recurso Humano,S.A</td>
</tr>
<tr>
<td colspan='4' style='height:30px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>NOMBRE:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>" . $name . "</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>PUESTO:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>" . $position . "</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>DEPARTAMENTO:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>" . $department . "</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>FECHA DE INGRESO:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>" . $hire . "</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>SALARIO MENSUAL:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>Q. " . $base . "</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>BONIFICACION INCENTIVO DECRETO:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>Q. 250.00</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>BONIFICACION INCENTIVO PACTADA:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>Q. " . $productivity . "</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td style='font-size:13px'>TOTAL:</td>
<td style=width:30px;></td>
<td style='font-size:13px'>Q. " . $total . "</td>
</tr>
<tr>
<td colspan='4' style='height:30px'></td>
</tr>
<tr>
<td colspan='4' style='text-align:center;font-size:13px'>BONIFICACIONES POR PRODUCTIVIDAD Y EFICIENCIA</td>
</tr>
<tr>
<td colspan='4' style='height:10px'></td>
</tr>
<tr>
<td colspan='4' style='font-size:13px'>Para los usos que a la persona interesada convenga se extiende la presente, " . $date . "</td>
</tr>
<td colspan='4' style='font-size:13px'>indicando que los datos de arriba descritos son válidos únicamente si la misma está debidamente firmada y sellada.</td>
</tr>
<tr>
<td colspan='4' style='text-align:center; font-size:13px'>Atentamente,</td>
</tr>
<tr>
<td colspan='4' style='height:30px'></td>
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
<td colspan='4' style=' text-align: center; font-size:13px'>" . $user . "
</tr>
<tr>
<td colspan='4' style='text-align:center;font-size:13px'>" . $job . "</td>
</tr>
<tr>
<td colspan='4' style='height:30px; border-bottom:solid 4px'></td>
</tr>
<tr>
<td colspan='4' style='height:1px; border-bottom:solid 1px'></td>
</tr>
<tr>
<td colspan='4' style='text-align:center' style='font-size:13px'>20 CALLE 05-25, ZONA 10 TORRE NEARSOL NIVEL 04</td>
</tr>
<tr>
<td colspan='4' style='text-align:center' style='font-size:13px'>PBX: 2504-2700 ext." . $contact ."</td>
</tr>
</table>
</div>
"
?>