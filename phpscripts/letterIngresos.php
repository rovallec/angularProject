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


echo "
<div style='margin-left:60px'>
<table>
<tr>
<td colspan='4'><img src='http://168.194.75.13/assets/Nearsol.png' style='height:60px; width:250px'></td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td colspan='4' style='text-align:center; text-decoration:underline'>CONSTANCIA LABORAL</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td colspan='4'>A quien interese:</td>
</tr>
<tr>
<td style=width:30px></td>
<td colspan='3'>Se hace constar que la información que se detalla acontinuación es real y Fidedigna, por haber sido extraida</td>
</tr>
<tr>
<td colspan='4'>de los controles internos del Departamento de Recursos Humanos de la entidad PRG Recurso Humano,S.A</td>
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
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>PUESTO:</td>
<td style=width:30px;></td>
<td>" . $position . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>DEPARTAMENTO:</td>
<td style=width:30px;></td>
<td>" . $department . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>FECHA DE INGRESO:</td>
<td style=width:30px;></td>
<td>" . $hire . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>SALARIO MENSUAL:</td>
<td style=width:30px;></td>
<td>Q. " . $base . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>BONIFICACION INCENTIVO DECRETO:</td>
<td style=width:30px;></td>
<td>Q. 250.00</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>BONIFICACION INCENTIVO PACTADA:</td>
<td style=width:30px;></td>
<td>" . $productivity . "</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td style=width:30px;></td>
<td>TOTAL:</td>
<td style=width:30px;></td>
<td>Q. " . $total . "</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
<tr>
<td colspan='4' style='text-align:center'>BONIFICACIONES POR PRODUCTIVIDAD Y EFICIENCIA</td>
</tr>
<tr>
<td colspan='4' style='height:20px'></td>
</tr>
<tr>
<td colspan='4'>Para los usos que a la persona interesada convenga se extiende la presente, " . $date . "</td>
</tr>
<td colspan='4'>indicando que los datos de arriba descritos son válidos únicamente si la misma está debidamente firmada y sellada.</td>
</tr>
<tr>
<td colspan='4' style='text-align:center'>Atentamente,</td>
</tr>
<tr>
<td colspan='4' style='height:40px'></td>
</tr>
</tr>
<tr>
<td colspan='4' style='height:150px'></td>
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
</div>
"
?>