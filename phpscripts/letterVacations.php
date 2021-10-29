<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

header("Content-type:application/pdf");
header("Content-Disposition:inline;filename='$filename");
readfile("downloaded.pdf");

require 'funcionesVarias.php';

try{
  $date = $_GET['date'];
}
catch (Exception $e){
  $diasIni = today();
}

try{
  $name = $_GET['name'];
}
catch (Exception $e){
  $name = '';
}

$today = formatLongDateToday();

$job = $_GET['job'];
$start_date = $_GET['start_date'];
$department = $_GET['department'];
$days_requested = $_GET['days_requested'];
$nearsol_id = $_GET['nearsol_id'];



echo "
<div style='margin-left:60px'>
  <table border='0' style='border-color: black; border: 1px; font-family: Arial;'> 
    <tr>
      <td colspan='2'><img src='../dist/assets/Nearsol.png' style='height:60px; width:250px'></td>
      <td style='font-size: 8px; font-weight: bold'>$nearsol_id</td>
    </tr>
    <tr>
    <tr>
      <td colspan='4' align- style='height:40px'></td>
    </tr>
    <td colspan='4' style='height:40px; font-size: 15px; font-weight: bold; text-align: center'>CONSTANCIA DE VACACIONES</td>
    </tr>
    <tr>
      <td colspan='4' style='margin-top: 50px;'>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='4'>&nbsp;</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td style='padding-left: 20px; width:180px; font-weight: bold;'>Nombre del empleado:</td>
      <td>$name</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td style='padding-left: 20px; width:180px; font-weight: bold;'>Fecha de ingreso:</td>
      <td>$start_date</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td style='padding-left: 20px; width:180px; font-weight: bold;'>Departamento:</td>
      <td>$department</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td style='padding-left: 20px; width:180px; font-weight: bold;'>Cargo:</td>
      <td>$job</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td style='padding-left: 20px; width:180px; font-weight: bold;'>Período de vacaiones:</td>
      <td>$date</td>
    </tr>
    <tr style=''>
      <td colspan='4' style='text-align:center;'>
        <table style='width: 99%;' align='center'>
          <tr>
            <td style='border-bottom: double; border-color: black;'>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr style='font-size: 12px;'>
      <td colspan='4' style='padding-left: 20px; width:180px; font-weight: bold;'>Dias solicitados para pago:</td>
    </tr>
    <tr style='font-size: 9px;'>
      <td style='width:30px'></td>
      <td>$date</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td colspan='3' style='text-align:right'>&nbsp;</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td colspan='3' style='text-align:right'>Total de días solicitados:</td>
      <td style='font-weight: bold;'>$days_requested</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td colspan='4'>&nbsp;</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td colspan='4' style='padding-left: 20px;'>
        <p style='white-space: nowrap; word-wrap: normal; flex-wrap: wrap-reverse;'>
          Extiendo la presente a favor de Nearsol, Sociedad Anonima para dejar constancia de que goce del total de días 
          <br> 
          de vacaciones indicado arriba.
        </p>
      </td>
    </tr>
    <tr style='font-size: 12px; height: 40px;'>
      <td style='' colspan='4'>&nbsp;</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td style='font-weight: bold; padding-left: 125px;' colspan='4'>Guatemala, $today</td>
    </tr>
    <tr style='font-size: 12px;'>
      <td style='height: 60px;' colspan='4'>&nbsp;</td>
    <tr style='font-size: 12px;'>
      <td style='' colspan='4'>
        <table style='width: 250px;' align='center'>
          <tr>
            <td style='border-bottom: solid black;'></td>
          </tr>
        </table>
      </td>
    <tr style='font-size: 12px;' align='center'>
      <td colspan='4'>$name</td>
    </tr>
  </table>
</div>"
?>
