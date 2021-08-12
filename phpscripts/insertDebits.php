<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idpayments = validarDatos($request->idpayments);
$type = validarDatos($request->type);
$amount = validarDatos($request->amount);

$sql = "INSERT INTO `debits` (`iddebits`, `id_payment`, `type`, `amount`) VALUES (null, $idpayments, '$type', '$amount');";
if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
}else{
    echo($sql);
}
?>