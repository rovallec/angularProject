<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

$date = $_GET['date'];
$name = $_GET['name'];
$puesto = $_GET['puesto'];
$departamento = $_GET['departamento'];
$start = $_GET['start'];
$user = $_GET['user'];
$contact = $_GET['contact'];
$job = $_GET['job'];
$iduser = $_GET['iduser'];
$f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
$t = explode(";", $start);
$start = $t[0] . " " . $f->format($t[1]);

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
<td colspan='4'>A Quien Interese:<td>
</tr>
<tr>
<td style='width:30px'></td>
<td colspan='3'>Se hace constar  que la información  que se detalla acontinuación es real y Fidedigna,</td>
</tr>
<tr>
<td colspan='4'>por  haber sido extraida de los  controles internos  del Departamento de Recursos Humanos de la</td>
</tr>
<tr>
<td colspan='4'>entidad PRG Recurso Humano,S.A.</td>
</tr>
<tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<td style='width:30px'></td>
<td>NOMBRE:</td>
<td style='width:30px'></td>
<td>" . $name . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style='width:30px'></td>
<td>PUESTO:</td>
<td style='width:30px'></td>
<td>" . $puesto . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style='width:30px'></td>
<td>DEPARTAMENTO:</td>
<td style='width:30px'></td>
<td>" . $departamento . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style='width:30px'></td>
<td>FECHA DE INGRESO:</td>
<td style='width:30px'></td>
<td>" . $start . "</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td colspan='4'>Para los usos  que a  la  persona  interesada  convenga  se  extiende  la  presente,</td>
</tr>
<tr>
<td colspan='4'>el " . $date . " indicando que  los datos  de arriba descritos</td>
</tr>
<tr>
<td colspan='4'>son válidos únicamente si la misma está debidamente firmada y sellada.</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td colspan='4' style=' text-align: center'>Atentamente,</td>
</tr>
<tr>
<td colspan='4' style='height:50px'></td>
</tr>
<tr>
<td colspan='4' style='text-alignt:center'>
<table styel='width:100%'>
<tr>
<td><img style='width:200px;height:100px;margin-left:175px' src='http://200.94.251.67/uploads/" . $iduser . "_Signatures_.jpeg'></td>
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
</div>"
?>
