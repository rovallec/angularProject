<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$iddebits = ($request->iddebits);
$idpayments = ($request->idpayments);
$type = ($request->type);
$amount = ($request->amount);

$sql = "INSERT INTO `debits` (`idcredits`, `id_payment`, `type`, `amount`) VALUES (null, $idpayments, '$type', '$amount');";
echo($sql);
#if(mysqli_query($con,$sql)){
#    echo(mysqli_insert_id($con));
#}
?>