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

function formatDatesPlus($adate, $plus) {
  $format = 'Y-m-d';

  if (is_numeric($adate)) {
    $adate = PHPExcel_Shared_Date::ExcelToPHP($adate+$plus);
    $date = new DateTime("@$adate");
    return $date->format($format);
  } else if (is_string($adate)) {
    $date = new DateTime($adate);
    return $date->format($format);
  } else {
    return $adate;
  }
}



function ifExist($adato) {
  if (!isset($adato) || empty($adato) || is_null($adato) || is_nan($adato)) {
    return false;
  } else {
    return true;
  }
}

function removeCommas($avalue) {
  $avalue = str_replace(',','',$avalue);
  return $avalue;
}

function addQuotes($str){
	if($str != 'NULL'){
		return "'" . $str . "'";
	}else{
		return $str;
	}
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

function number_letter($value) {
  $lang = 'es';
  $value = removeCommas($value);
  $f = new NumberFormatter($lang, NumberFormatter::SPELLOUT);
  $t = $f->format($value);
  //
  //$f = numfmt_create('es', NumberFormatter::SPELLOUT );
  //$t = numfmt_format($f, $value);


  return $t;
}

function number_letter_quetzales($value) {
  $value = removeCommas($value);
  $base_n_init = explode(".", number_format(((float)$value),2));
  $t = number_letter($base_n_init[0]) . ' quetzales con ' . number_letter($base_n_init[1]) . ' centavos';
  return $t;
}

function date_letter($value) {
  $values = explode('-', $value);
  if(count($values) == 3 && checkdate($values[1], $values[0], $values[2])) {
    $value = formatDates($value);
  }
  $letter = number_letter($values[0]) . " de " . getMonth($values[1]) . " del año " . number_letter($values[2]);
  return $letter;
}

function dpi_letter($value) {
  $value = removeCommas($value);
  $value = str_replace(' ', '', $value);
  $chunk = array();
  $chunk = str_split(strval($value));

  if (count($chunk) == 13) {
    $dpi1 = $chunk[0] . $chunk[1] . $chunk[2] . $chunk[3];
    $dpi2 = $chunk[4] . $chunk[5] . $chunk[6] . $chunk[7] . $chunk[8];
    $dpi3 = $chunk[9] . $chunk[10] . $chunk[11] . $chunk[12];

    $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    $t = '';

    $isCero = true;
    $letter = '';
    $i=0;
    while ($i <= 3) {
      if (($isCero) and ($chunk[$i]==0)) {
        $letter = $letter . ' cero ';
      } else {
        $isCero = false;
        $letter = $letter . $f->format($dpi1);
        $t = $t . $letter;
        break;
      }
      $i++;
    };

    $isCero = true;
    $letter = '';
    $i=4;
    while ($i <= 8) {
      if (($isCero) and ($chunk[$i]==0)) {
        $letter = $letter . ' cero ';
      } else {
        $isCero = false;
        $letter = $letter . $f->format($dpi2);
        $t = $t . ' espacio ' . $letter;
        break;
      }
      $i++;
    };

    $isCero = true;
    $letter = '';
    $i=9;

    while ($i <= 12) {
      if (($isCero) and ($chunk[$i]==0)) {
        $letter = $letter . 'cero ';
      } else {
        $isCero = false;
        $letter = $letter . $f->format($dpi3);
        $t = $t . ' espacio ' . $letter;
        break;
      }
      $i++;
    };
  } else {
    $t = '';
  }

  //$t = $t;
  return $t;
}

function formatLongDateToday() {
  $formatDate = date('d',);
  $formatDate = $formatDate . ' de ';
  $formatDate = $formatDate . getMonth(date('m'));
  $formatDate = $formatDate . ' del ';
  $formatDate = $formatDate . date('Y');
  return $formatDate;
}

function today() {
  $Today = formatDatesPlus(Date("y.m.d"), 0);
  return $Today;
}


?>
