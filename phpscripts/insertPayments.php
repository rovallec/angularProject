<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpayment = ($request->idpayments);
$credits = ($request->credits);
$debits = ($request->debits);
$date = ($request->date);

$sql = "UPDATE `payments` set `credits` = '$credits', `debits` = '$debits', `date` = '$date' WHERE `idpayments` = $idpayment";

if(mysqli_query($con, $sql)){
    echo("1");
}else{
    echo("0");
    http_response_code(404);
}
?>