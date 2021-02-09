<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpayment = validarDatos($request->idpayments);
$credits = validarDatos($request->credits);
$debits = validarDatos($request->debits);
$date = validarDatos($request->date);
$last_seventh = validarDatos($request->last_seventh);
$ot = validarDatos($request->ot);
$ot_hours = validarDatos($request->ot_hours);
$base_hours = validarDatos($request->base_hours);
$productivity_hours = validarDatos($request->productivity_hours);
$base = validarDatos($request->base);
$productivity = validarDatos($request->productivity);
$sevenths = validarDatos($request->seventh);
$holidays = validarDatos($request->holidays);
$holidays_hours = validarDatos($request->holidays_hours);
$base_complete = validarDatos($request->base_complete);
$productivity_complete = validarDatos($request->productivity_complete);
$id_account = validarDatos($request->id_account_py);

if($date != "NULL"){
    $date = "'".$date."'";
}


$sql =  "UPDATE `payments` set `credits` = $credits, `debits` = $debits, `date` = $date, " .
        "last_seventh = '$last_seventh', " .
        "ot = $ot, ot_hours = $ot_hours, base_hours = $base_hours, " .
        "productivity_hours = $productivity_hours, base = $base,  " .
        "productivity = $productivity, sevenths = $sevenths, " .
        "holidays = $holidays, holidays_hours = $holidays_hours, " .
        "base_complete = $base_complete, productivity_complete = $productivity_complete, " .
        "id_account_py = $id_account " .
        "WHERE `idpayments` = $idpayment";

        
if(mysqli_query($con, $sql)){
    echo("1");
}else{
    echo("0");
    echo($sql);
    http_response_code(404);
}
?>