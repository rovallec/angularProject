<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_profile = ($request->id_profile);
$bank = ($request->bank);
$account = ($request->account);


$sql = "UPDATE formeremployer SET 
    idemnization = '$idemnization',
    aguinaldo = '$aguinaldo',
    bono14 = '$bono14',
    igss = '$igss',
    taxpendingpayment = '$taxpendingpayment'
    WHERE `id_profile` = $id_profile";

if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
}
?>