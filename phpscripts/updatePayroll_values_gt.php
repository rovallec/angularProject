<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$discounted_hours = ($request->discounted_hours);
$discounted_days = ($request->discounted_days);
$idpayroll_values = ($request->idpayroll_values);
$ot_hours = ($request->ot_hours);
$str = "";

$sql = "UPDATE `payroll_values` SET `discounted_hours` = $discounted_hours, `discounted_days` = $discounted_days, `ot_hours` = $ot_hours WHERE `idpayroll_values` = $idpayroll_values;";

if(mysqli_query($con, $sql)){
    http_response_code(200);
    echo("1");
}else{
    $str = "0|" . mysqli_error($con);
    echo(json_encode($str));
}
?>