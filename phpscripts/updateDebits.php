<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idcredits = ($request->iddebits);
$status = ($request->status);

$sql = "UPDATE `debits` SET `status` = '$status' WHERE `iddebits` = $idcredits";

if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
}
?>