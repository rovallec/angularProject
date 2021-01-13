<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database_ph.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpayments = ($request->idpayments);
$type = ($request->type);
$amount = ($request->amount);

$sql = "INSERT INTO `credits` (`idcredits`, `id_payment`, `type`, `amount`) VALUES (null, $idpayments, '$type', '$amount');";

if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
}
?>