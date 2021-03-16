<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpaid_attendances = ($request->idpaid_attendances);
$worked = ($request->worked);
$balance = 0;
$id_payroll_value = ($request->id_payroll_value);
$date = ($request->date);
$str = "";

$sql = "UPDATE `paid_attendances` SET `worked` = (`worked` + $worked), `balance` = (`balance` + $worked) WHERE `id_payroll_value` = $id_payroll_value AND `date` = '$date';";

if(mysqli_query($con, $sql)){    
    echo("1");
}else{
    $str = "0|" . mysqli_error($con);
    echo(json_encode($str));
}
?>