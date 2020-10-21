<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->idperiods);

$sql = "UPDATE `periods` SET `status` = 0 WHERE `idperiods` = $id";
echo($sql);
if(mysqli_query($con,$sql)){
    echo(mysqli_insert_id($con));
}
?>