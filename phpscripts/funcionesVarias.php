<?php
require './Classes/PHPExcel.php';
function validarDatos($adato) {
  if (empty($adato) || is_null($adato)) {
    $adato = "NULL";
  } else {
    if ($adato == 'NaN') {
      $adato = 0;
    }
  }
  return $adato;
}

function validateDataToZero($adato) {
  if (empty($adato) || is_null($adato) || isset($adato)) {
    $adato = 0;
  }
  return $adato;
}

function validarDatosString($adato) {
  if (empty($adato) || is_null($adato)) {
    $adato = '';
  } 
  return $adato;
}

function formatDates($adate) {
  $format = 'Y-m-d';
  if (is_numeric($adate)) {
    $adate = PHPExcel_Shared_Date::ExcelToPHP($adate);
    $date = new DateTime("@$adate");
    return $date->format($format);
  } else if (is_string($adate)) {
    $date = new DateTime($adate);
    return $date->format($format);
  } else {
    return $adate;
  }
}

function removeCommas($avalue) {
  $avalue = str_replace(',','',$avalue);  
  return $avalue;
}

function getMonth($amonth){
  switch ((int)$amonth) {
    case 1:
      return 'Enero';
    break;
    case 2:
      return 'Febrero';
    break;
    case 3:
      return 'Marzo';
    break;
    case 4:
      return 'Abril';
    break;
    case 5:
      return 'Mayo';
    break;
    case 6:
      return 'Junio';
    break;
    case 7:
      return 'Julio';
    break;
    case 8:
      return 'Agosto';
    break;
    case 9:
      return 'Septiembre';
    break;
    case 10:
      return 'Octubre';
    break;
    case 11:
      return 'Noviembre';
    break;
    case 12:
      return 'Diciembre';
    break;
  }
}

?>
