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

?>