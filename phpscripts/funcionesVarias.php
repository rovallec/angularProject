<?php

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

?>
